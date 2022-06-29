<?php

namespace App\Http\Requests\price;

use Illuminate\Foundation\Http\FormRequest;

class PricesRequest extends FormRequest
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
            'name' => 'required|string',
            'is_virtual' => 'boolean|required',
            'currency_id' => 'required|exists:currencies,id',
            'original_price_id' => 'required_if:is_virtual,1:is_virtual,1|exists:prices,id',
            'original_percent' => 'required_if:is_virtual,1|numeric|min:0|max:100',
            'data' => 'nullable',
            'data.currency_name' => 'nullable',
            'data.original_price_name' => 'nullable',

        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
