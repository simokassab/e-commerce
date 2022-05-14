<?php

namespace App\Http\Requests\Labels;

use Illuminate\Foundation\Http\FormRequest;

class LableStorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->hasPermissions('permissions name');
//        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'entity' => 'required|in:'.config('app.validation_default_entities'),
            'color' => 'required',
            'image' => 'nullable',
            'key' => 'required',
        ];
    }

    public function message(){
        return [
            'title.required' => 'Please enter the :attribute',
            'entity.required' => 'Please enter the :attribute',
            'color.required' => 'Please enter the :attribute',

            'entity.in' => 'The entity must be one on the following: '.config('app.validation_default_entities'),

        ];
    }

}
