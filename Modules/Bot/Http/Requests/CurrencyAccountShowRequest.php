<?php


namespace App\Modules\Bot\Http\Requests;


use App\Rules\OAuthProvider;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyAccountShowRequest extends FormRequest
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
            'account_id' => ['required', 'max:32'],
            'platform' => ['required', new OAuthProvider],
        ];
    }
}
