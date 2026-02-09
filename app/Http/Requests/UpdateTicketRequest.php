<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['sometimes', 'required', 'string', 'min:5', 'max:120'],
            'descricao' => ['sometimes', 'required', 'string', 'min:20'],
            'prioridade' => ['sometimes', 'nullable', 'in:BAIXA,MEDIA,ALTA'],
            'responsavel_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'resolved_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
