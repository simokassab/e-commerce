<?php

namespace App\Http\Controllers\Brand;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand\Brand;
use App\Models\Brand\BrandField;
use App\Models\Brand\BrandLabel;
use App\Models\Field\Field;
use App\Models\Label\Label;
use Exception;
use Illuminate\Http\Request;

class BrandController extends MainController
{
    const OBJECT_NAME = 'objects.brand';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            $searchKeys=['name','code','meta_title','meta_description','meta_keyword','description'];
            return $this->getSearchPaginated(BrandResource::class, Brand::class,$request, $searchKeys);
        }
        return $this->successResponsePaginated(BrandResource::class,Brand::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fields= Field::with('fieldValue')
        ->whereEntity('brand')
        ->get();

        $labels= Label::whereEntity('brand')->get();

        return $this->successResponse(['fields'=>$fields,'labels'=>$labels]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = new Brand();
        $brand->name = json_encode($request->name);
        $brand->code = $request->code;
        if($request->image)
            $brand->image= $this->imageUpload($request->file('image'),config('images_paths.brand.images'));

        $brand->meta_title = json_encode($request->meta_title);
        $brand->meta_description = json_encode($request->meta_description);
        $brand->meta_keyword = json_encode($request->meta_keyword);
        $brand->description = json_encode($request->description);
        if(!($brand->save()))
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        $checkfields=true;
        $checklabels=true;

            //brands_fields validation and store
        if($request->has('fields')){
            $validatedFields = $request->validate([
                'fields.*.field_id' => 'required | exists:fields,id',
                'fields.*.field_value_id' => 'nullable | integer | exists:fields_values,id',
                'fields.*.value' => 'nullable',

            ]);
            if($validatedFields){

                $fieldsArray=$request->fields;

                foreach ($request->fields as $field => $value){
                    if($fieldsArray[$field]["type"]=='select'){
                        $fieldsArray[$field]["value"] = null;
                    }
                    $fieldsArray[$field]["field_value_id"] = null;
                    $fieldsArray[$field]["brand_id"] = $brand->id;
                    $fieldsArray[$field]["value"] = json_encode($request->fields[$field]['value']);
                    unset($fieldsArray[$field]['type']);

                }
                 $checkfields = BrandField::insert($fieldsArray);
            }}
            //brands_labels validation and store
        if ($request->has('labels')) {
            $validatedLabels = $request->validate([
                'labels.*.label_id' => 'required | exists:labels,id',
            ]);
            if($validatedLabels){

                $labelsArray=$request->labels;

                foreach ($request->labels as $label => $value){
                    $labelsArray[$label]["brand_id"] = $brand->id;
                }
                $checklabels = BrandLabel::insert($labelsArray);
            }}

        if(!$checkfields || !$checklabels)
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'brand' => new BrandResource($brand)
        ]);
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $this->successResponse(['brand' => new BrandResource($brand)]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {

        $brand->name = json_encode($request->name);
        $brand->code = $request->code;
        if($request->image){
            if( !$this->removeImage($brand->image) ){
                 throw new FileErrorException();
             }
            $brand->image= $this->imageUpload($request->file('image'),config('images_paths.brand.images'));

         }
        $brand->meta_title = json_encode($request->meta_title);
        $brand->meta_description = json_encode($request->meta_description);
        $brand->meta_keyword = json_encode($request->meta_keyword);
        $brand->description = json_encode($request->description);

        if(!($brand->save()))
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'brand' => new BrandResource($brand)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if(!$brand->delete())
           return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'brand' => new BrandResource($brand)
        ]);

}
public function toggleStatus(Request $request ,$id){

    $request->validate([
        'is_disabled' => 'boolean|required'
    ]);

    $brand = Brand::findOrFail($id);
    $brand->is_disabled = $request->is_disabled;
    if(!$brand->save())
        return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

    return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
        'brand' =>  new BrandResource($brand)
    ]);

}

public function getAllBrandsSorted(){
    $brands=Brand::order()->get();
    return $this->successResponse(['brands' => $brands ]);
}


public function updateSortValues(Request $request){

    batch()->update($brand = new Brand(),$request->order,'id');

    return $this->successResponsePaginated(BrandResource::class,Brand::class);

}
}
