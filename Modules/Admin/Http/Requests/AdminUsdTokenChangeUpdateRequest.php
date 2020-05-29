<?php


namespace App\Modules\Admin\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class AdminUsdTokenChangeUpdateRequest extends FormRequest
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
            'debt' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    $change = $this->route()->parameter('change');
                    $billing = clone $change->usdTokenTransaction->billing;
                    $billing->usd_tokens_num = $change->usdTokenTransaction->debt
                        ? $billing->usd_tokens_num + $change->amount
                        : $billing->usd_tokens_num - $change->amount;
                    if (!$billing->isPossibleChangeUsdToken($this->input())) {
                        $fail('Недостаточно средств на счете');
                    }
                }
            ]
        ];
    }
}
