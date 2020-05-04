<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\HasDifferentChars;
use App\Rules\Uuid4;

class RegisterRequest extends FormRequest
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
            'username' => [
                'required', 
                'string',
                'min:3',
                'max:32',
                'unique:user,username',
            ],
            'email' =>[
                'required',
                'email',
                'unique:user,email'
            ],
            'password' => [
                'required',
                'min:10',
                'max:255',
                'different:username',
                'different:email',
                new HasDifferentChars(5)
            ],
            'oauth_account' => [
                'nullable',
                new Uuid4,
            ],
        ];
    }
}
