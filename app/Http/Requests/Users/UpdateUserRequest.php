<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'username' => ['required', Rule::unique('users')->ignore($this->user->id)],

            'email' =>  ['required','email', Rule::unique('users')->ignore($this->user->id)],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'salt' => 'nullable',
            'role_id' => 'required|exists:roles,id'
        ];

    }
}
