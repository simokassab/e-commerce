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
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'salt' => 'nullable',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id'
        ];

        if($this->has('id')){
            $array['email'] =  "required|email|unique:users,email,$this->id,id";
            $array['username'] =  "required|string|unique:users,username,$this->id,id";
        }
        return $array;
    }
}
