<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $array = [
            'username' => 'required|unique:users,username | max:'.config('defaults.default_string_length_2'),
            'email' => 'required|email|unique:users,email | max:'.config('defaults.default_string_length_2'),
            'first_name' => 'required|string | max:'.config('defaults.default_string_length_2'),
            'last_name' => 'required|string | max:'.config('defaults.default_string_length_2'),
            'salt' => 'nullable | max:'.config('defaults.default_string_length_2'),
            'password' => 'required|min:8 | max:'.config('defaults.default_string_length_2'),
            'role_id' => 'required|exists:roles,id'
        ];

        if($this->has('id')){
            $array['id'] = 'required|numeric|exists:users,id';
            $array['email'] =  "required|email|unique:users,email,".$this->id.',id';
            $array['username'] =  "required|string|unique:users,username,".$this->id.',id';
        }
        return $array;
    }
}
