<?php


namespace App\Modules\Admin\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class AdminUsdTokenDestroyRequest extends FormRequest
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
            'change' => function ($attribute, $value, $fail) {
                $billing = clone $value->usdTokenTransaction->billing;
                $billing->usd_tokens_num = $value->usdTokenTransaction->debt
                    ? $billing->usd_tokens_num + $value->amount
                    : $billing->usd_tokens_num - $value->amount;
                if ($billing->usd_tokens_num < 0) {
                    $fail('Недостаточно средств на счете');
                }
            }
        ];
    }

    public function all($keys = null)
    {
        return [
            'change' => $this->route()->parameter('change')
        ];
    }
}
