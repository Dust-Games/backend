<?php

namespace App\Http\Requests\Api\OAuth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Uuid4;

class BindRequest extends FormRequest
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
            'code' => ['required'],
            'user_id' => ['required', new Uuid4],
        ];
    }
}
