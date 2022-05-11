<?php

namespace App\Http\Requests\Field;

use Illuminate\Foundation\Http\FormRequest;

class FieldsStorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //check if
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
            'type' => 'required|in:'.config('app.validation_default_type'),
            'entity' => 'required|in:'.config('app.validation_default_entities'),
            'is_required' => 'required|boolean',

        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The :attribute is required',
            'type.required' => 'The :attribute is required',
            'entity.required' => 'The :attribute is required',
            'is_required.required' => 'The :attribute is required',

            'type.in' => 'The :attribute is not a valid type',
            'entity.in' => 'The :attribute is not a valid type',

            'type.is_required' => 'The :attribute should be true or false',


        ];
    }
}
