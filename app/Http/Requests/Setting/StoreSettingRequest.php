<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'type' => 'required',
            'entity' => 'required',
            'is_required' => 'required'
        ];
    }

    public function messages()
    {
        return [


        'title.required' => 'the title field is required',
        'type.required' => 'the type field is required',
        'entity.required' => 'the entity field is required',
        'is_required.required' => 'the is_required field is required',
        ];
    }
}
