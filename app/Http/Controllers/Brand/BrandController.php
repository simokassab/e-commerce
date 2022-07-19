<?php

namespace App\Http\Controllers\Brand;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Brand\SingleBrandResource;
use App\Models\Brand\Brand;
use App\Models\Brand\BrandField;
use App\Models\Brand\BrandLabel;
use App\Models\Field\Field;
use App\Models\Label\Label;
use App\Services\Brand\BrandsService;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BrandController extends MainController
{
    const OBJECT_NAME = 'objects.brands';

//    public function __construct($defaultPermissionsFromChild = null)
//    {
//        parent::__construct(['BrancController@index' => ]);
//    }

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $fields= Field::with('fieldValue')->whereEntity('brand')->get();

        $labels= Label::whereEntity('brand')->get();

        return $this->successResponse(
            'Success!',
            [
                'fields'=>$fields,
                'labels'=>$labels
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBrandRequest $request)
    {
        DB::beginTransaction();
        try {
            //Brand Store
            $brand = new Brand();
            $brand->name = json_encode($request->name);
            $brand->code = $request->code;
            if($request->image)
                $brand->image= $this->imageUpload($request->file('image'),config('images_paths.brands.images'));

            $brand->meta_title = json_encode($request->meta_title);
            $brand->meta_description = json_encode($request->meta_description);
            $brand->meta_keyword = json_encode($request->meta_keyword);
            $brand->description = json_encode($request->description);
            $brand->save();
            //End of Brand Store

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
                        $fieldsArray[$field]["brand_id"] = $brand->id;
                        unset($fieldsArray[$field]['type']);
                    }
                      BrandField::insert($fieldsArray);
                }
                //End of Fields Store

                //Labels Store
                if ($request->has('labels')) {
                        $labelsArray=$request->labels;
                        foreach ($request->labels as $label => $value)
                            $labelsArray[$label]["brand_id"] = $brand->id;

                        BrandLabel::insert($labelsArray);
                    }
                    //End of Labels Store

                    DB::commit();

                    return $this->successResponse(
                        __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                        [
                            'brands' => new SingleBrandResource($brand)
                        ]
                    );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ,
                [
                    'brands' => new SingleBrandResource($brand),
                ]
            );

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $this->successResponse(['brands' => new BrandResource($brand)]);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreBrandRequest $request, Brand $brand)
    {
        DB::beginTransaction();
        try {

            BrandsService::deleteRelatedBrandFieldsAndLabels($brand);
            //Brand Store
            $brand->name = json_encode($request->name);
            $brand->code = $request->code;

            if($request->image)
                $brand->image= $this->imageUpload($request->file('image'),config('images_paths.brands.images'));

            $brand->meta_title = json_encode($request->meta_title);
            $brand->meta_description = json_encode($request->meta_description);
            $brand->meta_keyword = json_encode($request->meta_keyword);
            $brand->description = json_encode($request->description);
            $brand->is_disabled = 0;
            $brand->save();
            //End of Brand Store

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
                        $fieldsArray[$field]["brand_id"] = $brand->id;
                        unset($fieldsArray[$field]['type']);
                    }
                      BrandField::insert($fieldsArray);
                }
                //End of Fields Store

                //Labels Store
                if ($request->has('labels')) {
                        $labelsArray=$request->labels;
                        foreach ($request->labels as $label => $value)
                            $labelsArray[$label]["brand_id"] = $brand->id;

                        BrandLabel::insert($labelsArray);
                    }
                    //End of Labels Store

                    DB::commit();
                    return $this->successResponse(
                        __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
                        [
                            'brands' => new SingleBrandResource($brand)
                        ]
                    );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)]). "th error message: $e",
            );

        }catch(Error $error){
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.create',['name' => __(self::OBJECT_NAME)]),
            );
        }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  Brand  $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Brand $brand)
    {
        DB::beginTransaction();
        try {
            BrandsService::deleteRelatedBrandFieldsAndLabels($brand);
            $brand->delete();
            DB::commit();
            return $this->successResponse(
                __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                [
                    'brands' => new SingleBrandResource($brand)
                ]
            );

        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse( __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]));

        }


}
public function toggleStatus(Request $request ,$id){

    $request->validate([
        'is_disabled' => 'boolean|required'
    ]);

    $brand = Brand::findOrFail($id);
    $brand->is_disabled = $request->is_disabled;
    if(!$brand->save())
        return $this->errorResponse(__('messages.failed.update',['name' => __(self::OBJECT_NAME)]) );

    return $this->successResponse(
        __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
        [
            'brands' =>  new BrandResource($brand)
        ]
    );

}

public function getAllBrandsSorted(){
    $brands=Brand::order()->get();
    return $this->successResponse('Success!',['brands' => $brands ]);
}


public function updateSortValues(Request $request){

    batch()->update($brand = new Brand(),$request->order,'id');

    return $this->successResponsePaginated(BrandResource::class,Brand::class);

}

public function getTableHeaders(): \Illuminate\Http\JsonResponse
{
        return $this->successResponse( 'success' , ['headers' => __('headers.brands') ]);
}

}
