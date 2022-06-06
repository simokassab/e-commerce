<?php

namespace App\Http\Controllers\Country;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Countries\StoreCountryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use App\Models\Category\Category;
use App\Models\Country\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Constraint\Count;

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
            return $this->getSearchPaginated(CategoryResource::class,Category::class,$request->data[0],$request->limit);
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {

        $country = new Country();
        $country->name = json_encode($request->name);
        $country->iso_code_1 = $request->iso_code_1;
        $country->iso_code_2 = $request->iso_code_2;
        $country->phone_code = $request->phone_code;
        $country->flag = $request->flag;
        if($request->flag){
            $country->flag= $this->imageUpload($request->file('flag'),config('image_paths.country.images'));
        }
        if(!$country->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'country' => new CountryResource($country)
        ]);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        return $this->successResponse(['country' => new CountryResource($country)]);

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
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCountryRequest $request, Country $country)
    {
        $country->name = json_encode($request->name);
        $country->iso_code_1 = $request->iso_code_1;
        $country->iso_code_2 = $request->iso_code_2;
        $country->phone_code = $request->phone_code;
        if($request->flag){
            if( !$this->removeImage($country->image) ){
                 throw new FileErrorException();
             }
            $country->flag= $this->imageUpload($request->file('flag'),config('image_paths.country.images'));

         }
        if(!$country->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'country' => new CountryResource($country)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        if(!$country->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'country' => new CountryResource($country)
        ]);

    }
}
