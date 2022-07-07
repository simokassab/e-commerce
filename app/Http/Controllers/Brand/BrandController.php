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
    const OBJECT_NAME = 'objects.brand';

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
            $data= $this->getSearchPaginated(BrandResource::class, Brand::class,$request, $searchKeys);
            if($data->isEmpty()){
                 $data=[
                    'data' => [
                        [
                        'name'=>'',
                        'code'=> '',
                        'image'=> '',
                        'meta_title'=> '',
                        'meta_description'=> '',
                        'meta_description'=> '',
                        'meta_keyword'=> '',
                        'description'=> '',
                        'is_disabled'=> '',

                    ]
                    ]
                ];
                return response()->json($data);
                return  BrandResource::collection($data);
            }
            return $data;

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
        DB::beginTransaction();
        try {

            //Brand Store
            $brand = new Brand();
            $brand->name = json_encode($request->name);
            $brand->code = $request->code;
            if($request->image)
                $brand->image= $this->imageUpload($request->file('image'),config('images_paths.brand.images'));

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

                    return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                        'brand' => new SingleBrandResource($brand)
                    ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

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

        DB::beginTransaction();
        try {

            BrandsService::deleteRelatedBrandFieldsAndLabels($brand);
            //Brand Store
            $brand = new Brand();
            $brand->name = json_encode($request->name);
            $brand->code = $request->code;
            if($request->image)
                $brand->image= $this->imageUpload($request->file('image'),config('images_paths.brand.images'));
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
                    return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                    'brand' => new SingleBrandResource($brand)
                         ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse([
                'message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]),
                'errors' => $e->getMessage(),
        ]);

        }catch(Error $error){
            DB::rollBack();
            return $this->errorResponse([
                'message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]),
                'errors' => $error->getMessage(),
        ]);
        }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        DB::beginTransaction();
        try {
            BrandsService::deleteRelatedBrandFieldsAndLabels($brand);
            $brand->delete();
            DB::commit();
            return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'brand' => new SingleBrandResource($brand)
            ]);

        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        }


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

public function getTableHeaders(){
        return $this->successResponse(['headers' => __('headers.brands') ]);
}
}
