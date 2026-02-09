<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'min:5', 'max:120'],
            'descricao' => ['required', 'string', 'min:20'],
            'status' => ['nullable', 'in:ABERTO,EM_ANDAMENTO,RESOLVIDO'],
            'prioridade' => ['nullable', 'in:BAIXA,MEDIA,ALTA'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
            'resolved_at' => ['nullable', 'date'],
        ];
    }
}
