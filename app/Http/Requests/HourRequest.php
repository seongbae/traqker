<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HourRequest extends FormRequest
{
    public function rules()
    {
        return [
            'hours' => ['required'],
        ];
    }
}
