<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrency extends FormRequest
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
            'symbol' => 'required',
            'rate' => 'nullable|doubleval',
            'image' => 'nullable |max:'.config('app.default_image_size'),
            'sort' => 'required|integer'
         ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The field name is required.',
            'code.required' => 'The code is required.',
            'symbol.required' => 'The symbol is required.',
            'image.size' => 'The :attribute must be exactly :size.',
            'sort.required' => 'The sort is required.',
            'rate.doubleval' => 'The rate must be decimal.' ];
    }
}
