<?php

namespace App\Http\Requests\Field;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //check if the riole has permission
        return true;
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
            'type' => 'required | in:'.config('defaults.validation_default_type'),
            'entity' => 'required | in:'.config('defaults.validation_default_entities'),
            'is_required' => 'required | boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The :attribute is required',

            'type.required' => 'The :attribute is required',
            'type.in' => 'The :attribute is not a valid type',

            'entity.required' => 'The :attribute is required',
            'entity.in' => 'The :attribute is not a valid type',

            'is_required.required' => 'The :attribute is required',

            'is_required.required' => 'The :attribute field is required',
            'is_required.boolean' =>  'The :attribute field accepts only 0 or 1',


        ];
    }
}
