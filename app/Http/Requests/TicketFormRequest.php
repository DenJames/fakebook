<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ticket_category_id' => ['required', 'exists:ticket_categories,id'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ];
    }
}
