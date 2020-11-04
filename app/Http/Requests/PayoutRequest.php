<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayoutRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('payouts')->ignore($this->route('payout'))],
        ];
    }
}
