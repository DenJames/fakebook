<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BiographyFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'biography' => ['required', 'string', 'max:255'],
        ];
    }
}
