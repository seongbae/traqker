<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('projects')->ignore($this->route('project'))],
        ];
    }
}
