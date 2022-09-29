<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\MainRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;



class GetAllProdcutsForOrderRequest extends MainRequest

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
           'currency_id' => 'required|exists:currencies,id',
           'currency_rate' => 'numeric',
           'name' => 'nullable'
       ];
    }


}
