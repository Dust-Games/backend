<?php


namespace App\Modules\Bot\Http\Requests;


use App\Models\LeagueRow;
use Illuminate\Foundation\Http\FormRequest;

class SetLegueClassRequest extends FormRequest
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
            'accounts' => 'required|array',
            'accounts.*' => ['string', 'max:255',  function ($attribute, $value, $fail) {
                $league = LeagueRow::query()
                    ->where('account_id', $value)
                    ->where('score', 0)
                    ->orderByDesc('week')
                    ->first();
                if (!$league) $fail('Нет подходящей записи для account_id=' . $value);
            }],
            'class' => 'required|integer',
        ];
    }
}
