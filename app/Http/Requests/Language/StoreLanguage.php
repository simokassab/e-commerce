<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguage extends FormRequest
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
            'name' => 'required',
            'code' => 'required',
            'is_default' => 'nullable',
            'is_disabled' => 'nullable',
            'image' => 'max:'.config('app.default_image_size'),
            'sort' => 'required'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The field name is required.',
            'code.required' => 'The code is required.',
            'image.size' => 'The :attribute must be exactly :size.',
            'sort.required' => 'The sort is required.'
        ];
    }
}
