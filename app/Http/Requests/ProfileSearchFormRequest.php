<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSearchFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => ['required', 'string'],
        ];
    }
}