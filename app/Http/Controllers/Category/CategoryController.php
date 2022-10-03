<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\MainController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\RestFullCategoryResource;
use App\Http\Resources\Category\SelectCategoryResource;
use App\Http\Resources\Category\SingleCategoryResource;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Label\LabelsResource;
use App\Models\Category\Category;
use App\Models\Field\Field;
use App\Models\Label\Label;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends MainController
{
    const OBJECT_NAME = 'objects.category';
    const relations = ['parent', 'children', 'label', 'fields', 'fieldValue', 'tags'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */


    public function index(Request $request)
    {
        if ($request->method() == 'POST') {
            $searchKeys = ['id', 'name', 'code', 'slug', 'description'];
            $searchRelationsKeys = ['parent' => ['parent' => 'name',]];
            return $this->getSearchPaginated(CategoryResource::class, Category::class, $request, $searchKeys, self::relations, $searchRelationsKeys);
        }
        return $this->successResponsePaginated(CategoryResource::class, Category::class, self::relations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $fields = Field::with('fieldValue')->whereEntity('category')->get();
        $labels = Label::whereEntity('brand')->get();
        $parentCategories = Category::all();
        return $this->successResponse(
            data: [
                'fields' => FieldsResource::collection($fields),
                'labels' => LabelsResource::collection($labels),
                'categories' => SelectCategoryResource::collection($parentCategories)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {

        DB::beginTransaction();
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->code = 0;
            if ($request->image) {
                $category->image = $this->imageUpload($request->image, Category::$filePath['images']);
            }
            if ($request->icon) {
                $category->icon = $this->imageUpload($request->icon, Category::$filePath['icons']);
            }
            $category->parent_id = $request->parent_id;
            $category->slug = $request->slug;
            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
            $category->meta_keyword = $request->meta_keyword;
            $category->description = $request->description;

            $category->save();
            $category->code = $category->id;
            $category->save();

            if ($request->has('fields') && !is_null($request->fields)) {
                CategoryService::addFieldsToCategory($category, $request->fields);
            }

            if ($request->has('labels') && !is_null($request->labels)) {
                CategoryService::addLabelsToCategory($category, $request->labels);
            }

            DB::commit();
            return $this->successResponse(
                __('messages.success.create', ['name' => __(self::OBJECT_NAME)]),
                [
                    'category' => new SingleCategoryResource($category->load(['fieldValue', 'label', 'parent']))
                ]
            );
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return $this->errorResponse(__('messages.failed.create', ['name' => __(self::OBJECT_NAME)]) . ' error message: ' . $e);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {

        return $this->successResponse(
            data: [
                'category' => new SingleCategoryResource($category->load(['fields', 'fieldValue', 'label']))
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        DB::beginTransaction();
        try {

            $category->name = $request->name;
            $category->code = 0;
            if ($request->image) {
                $category->image = $this->imageUpload($request->image, Category::$filePath['images']);
            }
            if ($request->icon) {
                $category->icon = $this->imageUpload($request->icon, Category::$filePath['icons']);
            }
            $category->parent_id = $request->parent_id;
            $category->slug = $request->slug;
            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
            $category->meta_keyword = $request->meta_keyword;
            $category->description = $request->description;

            $category->save();
            $category->code = $category->id;
            $category->save();

            CategoryService::deleteRelatedCategoryFieldsAndLabels($category);

            if ($request->has('fields') && !is_null($request->fields)) {
                CategoryService::addFieldsToCategory($category, $request->fields);
            }

            if ($request->has('labels') && !is_null($request->labels)) {
                $oldLabel = $request->labels;
                if (gettype($request->labels) == 'string') {
                    $request->labels = explode(",", $request->labels);
                    if (count($request->labels) <= 0) {
                        $request->labels = $oldLabel;
                    }
                }
                CategoryService::addLabelsToCategory($category, $request->labels);
            }

            DB::commit();
            return $this->successResponse(
                __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
                [
                    'category' => new SingleCategoryResource($category->load(['fieldValue', 'label', 'parent']))
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(__('messages.failed.update', ['name' => __(self::OBJECT_NAME)]) . ' error message: ' . $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return string
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $message = '';
            if (!$category->canDelete($message)) {
                return $this->errorResponse($message);
            }
            CategoryService::deleteRelatedCategoryFieldsAndLabels($category);
            $category->delete();
            DB::commit();
            return $this->successResponse(
                __('messages.success.delete', ['name' => __(self::OBJECT_NAME)]),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(__('messages.failed.delete', ['name' => __(self::OBJECT_NAME)])) . ' error: ' . $e;
        }
    }


    public function toggleStatus(Request $request, $id)
    {

        $request->validate(['is_disabled' => 'boolean|required']);
        $category = Category::findOrFail($id);
        $category->is_disabled = $request->is_disabled;
        if (!$category->save())
            return $this->errorResponse(__('messages.failed.update', ['name' => __(self::OBJECT_NAME)]));

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'category' => new CategoryResource($category)
            ]
        );
    }

    public function getAllParentsSorted()
    {

        $categories = Category::rootParent()->order()->get();
        return $this->successResponse(
            data: [
                'categories' => $categories
            ]
        );
    }

    public function getAllChildsSorted($parent_id)
    {
        $categories = Category::whereParentId($parent_id)->order()->get();
        return $this->successResponse(
            data: [
                'categories' => $categories
            ]
        );
    }

    public function updateSortValues(StoreCategoryRequest $request)
    {

        batch()->update($category = new Category(), $request->order, 'id');
        return $this->successResponsePaginated(CategoryResource::class, Category::class, self::relations);
    }

    public function getTableHeaders()
    {
        return $this->successResponse(data: ['headers' => __('headers.categories')]);
    }

    public function getCategoiresData()
    {
        return $this->successResponsePaginated(RestFullCategoryResource::class, Category::class, ['fields', 'label']);
    }
}
