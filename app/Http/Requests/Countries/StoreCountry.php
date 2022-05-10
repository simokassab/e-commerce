<?php

namespace App\Http\Requests\Countries;

use Illuminate\Foundation\Http\FormRequest;


class StoreCountry extends FormRequest
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
            'name' => 'required|json',
            'iso_code_1' => 'required',
            'iso_code_2' => 'required',
            'phone_code' => ['required','regex:/^\+\d{1,3}$/'],
            'flag' => 'required |max:'.config('app.default_image_size')

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The field name is required.',
            'iso_code_1.required' => 'The 1st iso code is requrired.',
            'is_code_2.required'  => 'The 2nd iso code is requrired.',
            'phone_code.required' => 'The phone code is required.',
            'flag.required' => 'The flag image is required.',
            'flag.size' => 'The :attribute must be exactly :size.',
        ];
    }
}
