<?php


namespace App\Modules\Admin\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => 'numeric|exists:role,id',
            'sub_roles' => 'array',
            'sub_roles.*' => 'numeric|exists:sub_roles,id',
        ];
    }
}
