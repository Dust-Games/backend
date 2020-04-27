<?php

namespace App\Http\Requests\Bot;

use Illuminate\Foundation\Http\FormRequest;

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
                'integer'
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
