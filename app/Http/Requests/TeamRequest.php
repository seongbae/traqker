<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('teams')->ignore($this->route('team'))],
        ];
    }
}
