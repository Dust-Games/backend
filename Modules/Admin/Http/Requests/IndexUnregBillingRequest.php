<?php

namespace App\Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexUnregBillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'q' => 'string|max:32',
            'order_by' => 'string|in:username,account_id,dust_coins_num',
            'order_desc' =>[
                'string',
                'in:username,account_id,dust_coins_num',
                function ($attribute, $value, $fail) {
                    if ($this->input('order_by')) {
                        $fail('validtion.invalid');
                    }
                }
            ],
        ];
    }
}
