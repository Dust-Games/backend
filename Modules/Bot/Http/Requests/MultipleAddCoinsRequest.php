<?php

namespace App\Modules\Bot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\OAuthProvider;

class MultipleAddCoinsRequest extends FormRequest
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
            'accounts' => [
                'required',
                'array'
            ],

            'platform' => [
                'required',
                new OAuthProvider,
            ],
            
            'dust_coins_num' => [
                'required',
                'numeric',
                'min:0',
                'max:1000000000'
            ],
        ];
    }
}
