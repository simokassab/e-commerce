<?php

namespace App\Http\Controllers\Label;

use App\Http\Requests\Labels\LableStorRequest;
use App\Models\RolesAndPermissions\CustomRole;
use App\Http\Resources\LabelsResource;
use App\Models\Label\Label;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

class LabelController extends MainController
{
    const OBJECT_NAME = 'objects.label';

    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse(['labels' => LabelsResource::collection(Label::all())]);

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

        if(!$label->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'label' => new LabelsResource($label)
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return $this->successResponse(['label' => new LabelsResource($label)]);

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
        $label->title = json_encode($request->title);
        $label->entity = ($request->entity);
        $label->color = ($request->color);
        $label->image = ($request->image);
        $label->key = ($request->key);

        if(!$label->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'label' => new LabelsResource($label)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Label $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        if(!$label->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'label' => new LabelsResource($label)
        ]);


}
}
