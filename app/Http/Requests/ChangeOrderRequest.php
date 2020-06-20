<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeOrderRequest extends FormRequest
{
    private $rules = [
        'api.order.close-order' => [],
        'api.order.credit-order' => [
            'amount' => 'required|numeric|min:0.01'
        ],
        'api.order.debit-order' => [
            'amount' => 'required|numeric|min:0.01'
        ]
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return
            $this->route()->parameter('order')->parentalCurrencyAccount->owner_id
            ===
            $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules[$this->route()->getName()];
    }
}
