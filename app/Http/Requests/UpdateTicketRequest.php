<?php

namespace App\Http\Requests;

use App\Enums\Prioridade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'prioridade' => Prioridade::tryFromMixed($this->input('prioridade'))?->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'titulo' => ['sometimes', 'required', 'string', 'min:5', 'max:120'],
            'descricao' => ['sometimes', 'required', 'string', 'min:20'],
            'prioridade' => ['sometimes', 'nullable', Rule::in(Prioridade::values())],
            'status' => ['sometimes', 'nullable', Rule::in(['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'])],
            'responsavel_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'resolved_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
