<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse(['categories' => CategoryResource::collection(Category::with('parent','children','label','fields','fieldValue','tags','discount','brand','productCategory')->get())]);
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
        $category->image= $request->image;
        $category->icon= $request->icon;
        $category->parent_id= $request->parent_id;
        $category->slug= $request->slug;
        $category->title= json_encode($request->title);
        $category->description= json_encode($request->description);
        $category->keyword= json_encode($request->keyword);
        $category->sort= $request->sort;
        $category->is_disabled= $request->is_disabled;

        if(!$category->save())
            return $this->errorResponse([__('messages.failed.create'),['name' => __('objects.category')]]);

        return $this->successResponse([__('messages.success.create'),['name' => __('objects.category')],
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
    public function update(Request $request, Category $category)
    {
        $category->name= json_encode($request->name);
        $category->code= $request->code;
        $category->image= $request->image;
        $category->icon= $request->icon;
        $category->parent_id= $request->parent_id;
        $category->slug= $request->slug;
        $category->title= json_encode($request->title);
        $category->description= json_encode($request->description);
        $category->keyword= json_encode($request->keyword);
        $category->sort= $request->sort;
        $category->is_disabled= $request->is_disabled;

        if(!$category->save())
            return $this->errorResponse([__('messages.failed.update'),['name' => __('objects.category')]]);

        return $this->successResponse([__('messages.success.update'),['name' => __('objects.category')],
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
            return $this->errorResponse([__('messages.failed.delete'),['name' => __('objects.category')]]);

            return $this->successResponse([__('messages.success.delete'),['name' => __('objects.category')],
                'category' =>  new CategoryResource($category)
            ]);


    }
}

