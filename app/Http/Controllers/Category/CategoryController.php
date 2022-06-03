<?php

namespace App\Http\Controllers\Category;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Countries\StoreCountryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category\Category;
use App\Services\Category\CategoryService;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends MainController
{
    const OBJECT_NAME = 'objects.category';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponsePaginated(CategoryResource::class,Category::class,['parent','children','label','fields','fieldValue','tags']);
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category=new Category();
            $category->name= json_encode($request->name);
            $category->code= $request->code;

            if($request->image){
                $category->image= $this->imageUpload($request->file('image'),config('image_paths.category.images'));
            }
            if($request->icon){
                $category->icon= $this->imageUpload($request->file('icon'),config('image_paths.category.icons'));
            }
            $category->parent_id= $request->parent_id;
            $category->slug= $request->slug;
            $category->meta_title= json_encode($request->meta_title);
            $category->meta_description= json_encode($request->meta_description);
            $category->meta_keyword= json_encode($request->meta_keyword);
            $category->description= json_encode($request->description);
            $category->sort= Category::getMaxSortValue($request->parent_id ? NULL:$request->parent_id);


            if(!$category->save())
                return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);
        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'category' =>  new CategoryResource($category)
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->successResponse(['category' =>  new CategoryResource($category)]);

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
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {

        $category->name= json_encode($request->name);
        $category->code= $request->code;
        if($request->image){
           if( !$this->removeImage($category->image) ){
                throw new FileErrorException();
            }
           $category->image= $this->imageUpload($request->file('image'),config('image_paths.category.images'));

        }
        if($request->icon){
            if( !$this->removeImage($category->icon)){
                throw new FileErrorException();
            }
           $category->icon= $this->imageUpload($request->file('icon'),config('image_paths.category.icons'));

        }
        $category->parent_id= $request->parent_id;
        $category->slug= $request->slug;
        $category->meta_title= json_encode($request->meta_title);
        $category->meta_description= json_encode($request->meta_description);
        $category->meta_keyword= json_encode($request->meta_keyword);
        $category->description= json_encode($request->description);
        $category->is_disabled= $request->is_disabled;

        if(!$category->save())
             return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'category' =>  new CategoryResource($category)
        ]);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(!$category->delete())
          return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'category' =>  new CategoryResource($category)
        ]);


    }


    public function toggleStatus(Request $request ,$id){

        $request->validate([
            'is_disabled' => 'boolean|required'
        ]);

            $category = Category::findOrFail($id);
        $category->is_disabled=$request->is_disabled;
        if(!$category->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'category' =>  new CategoryResource($category)
        ]);

    }

    public function getAllParentsSorted(){

        $categories=Category::rootParent()->order()->get();
        return $this->successResponse(['categories' => $categories ]);

    }

    public function getAllChildsSorted($parent_id){

        $categories=Category::whereParentId($parent_id)->order()->get();
        return $this->successResponse(['categories' => $categories ]);
    }

    public function updateSortValues(Request $request){

        $category = new Category();
        $order = $request->order;
        $index = 'id';

        batch()->update($category,$order,$index);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
        'category' =>  new CategoryResource($category)
    ]);

    }



}

