<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\MainController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\SingleCategoryResource;
use App\Models\Category\CategoriesFields;
use App\Models\Category\CategoriesLabels;
use App\Models\Category\Category;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends MainController
{
    const OBJECT_NAME = 'objects.category';
    const relations=['parent','children','label','fields','fieldValue','tags'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */

    public function index(Request $request)
    {

        if ($request->method()=='POST') {

            $searchKeys=['name','code','slug','description'];
            $searchRelationsKeys = ['parent' =>['parent_name' => 'name',]];
            return $this->getSearchPaginated(CategoryResource::class, Category::class, $request, $searchKeys,self::relations,$searchRelationsKeys);

          }
        return $this->successResponsePaginated(CategoryResource::class,Category::class,self::relations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $category=new Category();
            $category->name= json_encode($request->name);
            $category->code= $request->code;
            if($request->image){
                $category->image= $this->imageUpload($request->file('image'),config('images_paths.category.images'));
            }
            if($request->icon){
                $category->icon= $this->imageUpload($request->file('icon'),config('images_paths.category.icons'));
            }
            $category->parent_id= $request->parent_id;
            $category->slug= $request->slug;
            $category->meta_title= json_encode($request->meta_title);
            $category->meta_description= json_encode($request->meta_description);
            $category->meta_keyword= json_encode($request->meta_keyword);
            $category->description= json_encode($request->description);
            $category->save();

            //Fields Store
            if($request->has('fields')){
                    $fieldsArray=$request->fields;
                    foreach ($request->fields as $field => $value){
                        if($fieldsArray[$field]["type"]=='select')
                            $fieldsArray[$field]["value"] = null;
                        else{
                            $fieldsArray[$field]["field_value_id"] = null;
                            $fieldsArray[$field]["value"] = json_encode($value['value']);

                        }
                        $fieldsArray[$field]["category_id"] = $category->id;
                        unset($fieldsArray[$field]['type']);

                    }
                      CategoriesFields::insert($fieldsArray);
                }
                //End of Fields Store

                 //Labels Store
                 if ($request->has('labels')) {
                        $labelsArray=$request->labels;
                        foreach ($request->labels as $label => $value)
                            $labelsArray[$label]["category_id"] = $category->id;

                        CategoriesLabels::insert($labelsArray);
                    }
                    //End of Labels Store
                    DB::commit();
                    return $this->successResponse(
                        __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                        [
                            'cateogries' => new SingleCategoryResource($category)
                        ]
                    );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) );

            }
     }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return $this->successResponse(
            'Success!',
            [
                'category' =>  new CategoryResource($category)
            ]
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        DB::beginTransaction();
        try {

            CategoryService::deleteRelatedCategoryFieldsAndLabels($category);

            $category->name= json_encode($request->name);
            $category->code= $request->code;
            if($request->image){
                $category->image= $this->imageUpload($request->file('image'),config('images_paths.category.images'));
            }
            if($request->icon){
                $category->icon= $this->imageUpload($request->file('icon'),config('images_paths.category.icons'));
            }
            $category->parent_id= $request->parent_id;
            $category->slug= $request->slug;
            $category->meta_title= json_encode($request->meta_title);
            $category->meta_description= json_encode($request->meta_description);
            $category->meta_keyword= json_encode($request->meta_keyword);
            $category->description= json_encode($request->description);
            $category->save();

             //Fields Store
             if($request->has('fields')){
                $fieldsArray=$request->fields;
                foreach ($request->fields as $field => $value){
                    if($fieldsArray[$field]["type"]=='select')
                        $fieldsArray[$field]["value"] = null;
                    else{
                        $fieldsArray[$field]["field_value_id"] = null;
                        $fieldsArray[$field]["value"] = json_encode($value['value']);

                    }
                    $fieldsArray[$field]["category_id"] = $category->id;
                    unset($fieldsArray[$field]['type']);

                }
                  CategoriesFields::insert($fieldsArray);
            }
            //End of Fields Store

             //Labels Store
             if ($request->has('labels')) {
                    $labelsArray=$request->labels;
                    foreach ($request->labels as $label => $value)
                        $labelsArray[$label]["category_id"] = $category->id;

                    CategoriesLabels::insert($labelsArray);
                }
                //End of Labels Store

                    DB::commit();
                    return $this->successResponse(
                        __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
                        [
                            'category' => new SingleCategoryResource($category)
                        ]
                    );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) );

            }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            CategoryService::deleteRelatedCategoryFieldsAndLabels($category);
            $category->delete();
            DB::commit();
            return $this->successResponse(
                __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                [
                    'category' => new SingleCategoryResource($category)
                ]
            );

        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse( __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) );

        }


    }


    public function toggleStatus(Request $request ,$id){

        $request->validate([
            'is_disabled' => 'boolean|required'
        ]);

            $category = Category::findOrFail($id);
        $category->is_disabled=$request->is_disabled;
        if(!$category->save())
            return $this->errorResponse(__('messages.failed.update',['name' => __(self::OBJECT_NAME)]) );

        return $this->successResponse(
            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            [
                'category' =>  new CategoryResource($category)
            ]
        );

    }

    public function getAllParentsSorted(){

        $categories=Category::rootParent()->order()->get();
        return $this->successResponse(
            'Success!',
            [
                'categories' => $categories
            ]
        );

    }

    public function getAllChildsSorted($parent_id){
        $categories=Category::whereParentId($parent_id)->order()->get();
        return $this->successResponse(
            'Success!',
            [
                'categories' => $categories
            ]
        );
    }

    public function updateSortValues(Request $request){

        batch()->update($category = new Category(),$request->order,'id');
            return $this->successResponsePaginated(CategoryResource::class,Category::class,self::relations);
    }

    public function getTableHeaders(){
        return $this->successResponse('Success!',['headers' => __('headers.categories') ]);
}

}

