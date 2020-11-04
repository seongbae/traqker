<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('clients')->ignore($this->route('client'))],
        ];
    }
}
