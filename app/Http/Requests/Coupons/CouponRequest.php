<?php

namespace App\Http\Requests\Coupons;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends MainRequest
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
            'code' => 'required|unique:coupons,code',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'type' => ['required',Rule::in(['percentage','amount'])],
            'value' => 'required|numeric',
            'min_amount' => 'nullable|numeric',
            'is_one_time' => 'nullable|boolean',
            'is_used' => 'nullable|boolean',
        ];
    }
}
