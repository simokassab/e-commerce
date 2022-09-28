<?php

namespace App\Http\Requests\Tag;

use App\Http\Requests\MainRequest;

class StoreTagRequest extends MainRequest
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
            'name.en' => 'required',
            'name.ar' => 'required'
        ];
    }

    public function messages()
    {
        return [
        'name.required' => 'the :attribute field is required.'
    ];
    }
}
