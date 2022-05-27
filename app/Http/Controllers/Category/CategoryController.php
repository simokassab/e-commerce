<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category\Category;
use App\Services\Category\CategoryService;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return $this->successResponse(['categories' => CategoryResource::collection( Category::with('parent','children','label','fields','fieldValue','tags','discount','brand','products')->get() )]);

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

        // should be repeated
        $category->image= $request->image;
        // should be repeated

        $category->icon= $request->icon;
        $category->parent_id= $request->parent_id;
        $category->slug= $request->slug;
        $category->meta_title= json_encode($request->meta_title);
        $category->meta_description= json_encode($request->meta_description);
        $category->meta_keyword= json_encode($request->meta_keyword);
        $category->description= json_encode($request->description);
        $category->sort= Category::getChildsMaxSortValue($request->parent_id ? NULL:$request->parent_id);

        $category->is_disabled= $request->is_disabled;

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
        $category->image= $request->image;
        $category->icon= $request->icon;
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

        $categories=Category::whereNull('parent_id')->orderByRaw('ISNULL(sort), sort ASC')->get();
        return $this->successResponse(['categories' => $categories ]);
    }

    public function getAllChildsSorted($parent_id){

        $categories=Category::where('parent_id',$parent_id)->orderByRaw('ISNULL(sort), sort ASC')->get();
        return $this->successResponse(['categories' => $categories ]);
    }


    public function updateSortValues($parent_id){

        $category = new Category();
        $data=[

            //array['id' => 1 , 'sort' => 1]
        ];

        $index = 'id';

      batch()->update($category,$data,$index);


      return "test";

    }

}

