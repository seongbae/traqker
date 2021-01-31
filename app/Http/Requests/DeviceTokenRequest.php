<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => ['required'], // Rule::unique('device_tokens')->ignore($this->route('device_token'))],
            'device_token'=>['required']
        ];
    }
}
