<?php

namespace App\Http\Requests;

use App\Enums\Prioridade;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => TicketStatus::tryFromMixed($this->input('status'))?->value,
            'prioridade' => Prioridade::tryFromMixed($this->input('prioridade'))?->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'min:5', 'max:120'],
            'descricao' => ['required', 'string', 'min:20'],
            'status' => ['nullable', Rule::in(TicketStatus::values())],
            'prioridade' => ['nullable', Rule::in(Prioridade::values())],
            'solicitante_id' => ['nullable', 'exists:users,id'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
            'resolved_at' => ['nullable', 'date'],
        ];
    }
}
