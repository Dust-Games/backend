<?php


namespace App\Modules\Bot\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class OauthRequest extends FormRequest
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
            'oauth_provider_id' => 'required|integer',
            'account_id' => 'required|string|max:32',
        ];
    }

}
