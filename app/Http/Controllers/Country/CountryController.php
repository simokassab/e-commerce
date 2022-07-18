<?php

namespace App\Http\Controllers\Country;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Countries\StoreCountryRequest;
use App\Http\Resources\Country\CountryResource;
use App\Models\Country\Country;
use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\Country\CoutnrySingleResource;
class CountryController extends MainController
{
    const OBJECT_NAME = 'objects.country';

    public function __construct($defaultPermissionsFromChild = null)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {

            $searchKeys=['name','iso_code_1','iso_code_2','phone_code'];
            return $this->getSearchPaginated(CountryResource::class, Country::class,$request, $searchKeys);
        }
        return $this->successResponsePaginated(CountryResource::class,Country::class);
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
    public function store(StoreCountryRequest $request)
    {

        $country = new Country();
        $country->name = json_decode($request->name);
        $country->iso_code_1 = $request->iso_code_1;
        $country->iso_code_2 = $request->iso_code_2;
        $country->phone_code = $request->phone_code;
        $country->flag = $request->flag;
        if($request->flag){
            $country->flag= $this->imageUpload($request->file('flag'),config('images_paths.country.images'));
        }
        if(!$country->save())
            return $this->errorResponse( __('messages.failed.create',['name' => __(self::OBJECT_NAME)]));

        return $this->successResponse(
            __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            [
                'country' => new CoutnrySingleResource($country)
            ]
        );

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Country $country)
    {
        return $this->successResponse(
            'Success!',
            [
                'country' => new CoutnrySingleResource($country)
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


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreCountryRequest $request, Country $country)
    {
        $country->name = ($request->name);
        $country->iso_code_1 = $request->iso_code_1;
        $country->iso_code_2 = $request->iso_code_2;
        $country->phone_code = $request->phone_code;
        if($request->flag){
            if(!$this->removeImage($country->image) ){
                 throw new FileErrorException();
             }
            $country->flag= $this->imageUpload($request->file('flag'),config('images_paths.country.images'));

         }
        if(!$country->save())
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            [
                'country' => new CoutnrySingleResource($country)
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Country $country)
    {
        if(!$country->delete())
            return $this->errorResponse(
                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            [
                'country' => new CoutnrySingleResource($country)
            ]
    );

    }


    public function getTableHeaders(){
        return $this->successResponse('Success!',['headers' => __('headers.countries') ]);
}
}
