<?php

namespace App\Http\Requests\RolesAndPermissions;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => 'required | max:'.config('defaults.default_string_length'),
//            'permissions.*' => 'nullable|exists:Spatie\Permission\Models\Permission,id',
            'parent_id' => 'required|integer|exists:Spatie\Permission\Models\Role,id',
            "permissions" => 'required',
        ];
    }

    public function messages()
    {
        return [
//            'name.required' => "The role's name is required:",
//            'permissions.*.exists' => 'One of the permissions that you have selected is not valid',
//            'parent_id.exists' => 'The parent role that you chose is not valid',
        ];
    }
}
