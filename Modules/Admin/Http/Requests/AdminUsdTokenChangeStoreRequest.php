<?php


namespace App\Modules\Admin\Http\Requests;


use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Uuid;

class AdminUsdTokenChangeStoreRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0',
            'debt' => 'required|boolean',
            'user_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Uuid::isValid($value)) {
                        $fail('ID пользователя не UUID');
                        return;
                    }
                    $user = User::with('billing')->find($value);
                    if (!$user) {
                        $fail('Пользователь отсутствует');
                        return;
                    }
                    if (!$user->billing) $user->billing()->create();
                    if (!$user->billing->isPossibleChangeUsdToken($this->input())) {
                        $fail('Недостаточно средств на счете');
                        return;
                    }
                    $this->merge(['billing' => $user->billing]);
                }
            ]
        ];
    }
}
