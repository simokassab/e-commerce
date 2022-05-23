<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
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
            'start_date' => 'required | date',
            'end_date' => 'after:start_date',
            'discount_percentage' => 'required | between:0,100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the name field is required',
            'start_date.required' => 'the name field is required',
            'end_date.after' => 'the date should be greater than the start_date',
            'discount_percentage.between' => 'the discount should be between 0 and 100 percent',
            'discount_percentage.required' => 'the discount_percentage field is required',

        ];
    }
}
