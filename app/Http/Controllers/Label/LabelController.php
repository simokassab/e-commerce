<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Http\Requests\Labels\LableStorRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\RolesResource;
use App\Models\RolesAndPermissions\CustomRole;
use Illuminate\Http\Request;
use App\Http\Resources\LabelsResource;
use App\Models\Label\Label;
use App\Http\Controllers\MainController;
class LabelController extends MainController
{

    public function __construct()
    {
        $this->map_permissions = [
            'LabelController@index' => 'LabelController@store'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //check
        return response()->json(['data' => [
            'labels' =>LabelsResource::collection(Label::all()),
        ]
        ],202);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LableStorRequest $request)
    {
        $label = new Label();

        $label->title = json_encode($request->title);
        $label->entity = ($request->entity);
        $label->color = ($request->color);
        $label->image = ($request->image);
        $label->key = ($request->key);

        if(!$label->save()){
            return response('Error while create new label please try again later! ' ,500);
        }

        return response()->json(['data' => [
            'message' => 'The label has been created',
            'role' => new LabelsResource($label),
        ]],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return response()->json(['data' => [
            'label' =>new LabelsResource($label),
        ]
        ],200);
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
    public function update(LableStorRequest $request, Label $label)
    {
        if(!auth()->hasPermissions('permissions name'))
            return response();

        $label->title = json_encode($request->title);
        $label->entity = ($request->entity);
        $label->color = ($request->color);
        $label->image = ($request->image);
        $label->key = ($request->key);

        if(!$label->save()){
            return response('Error while saving the label please try again later! ' ,500);
        }

        return response()->json(['data' => [
            'message' => 'The label has been created',
            'label' => new LabelsResource($label),
        ]],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Label $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        if(!$label->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The currency was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'currency deleted successfully',
                'currency' => new LabelsResource($label)
            ]

        ],201);
    }
}
