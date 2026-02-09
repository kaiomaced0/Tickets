<?php

namespace App\Http\Requests;

use App\Enums\Prioridade;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListTicketsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('active')) {
            $this->merge([
                'active' => filter_var($this->input('active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }

        $this->merge([
            'status' => TicketStatus::tryFromMixed($this->input('status'))?->value,
            'prioridade' => Prioridade::tryFromMixed($this->input('prioridade'))?->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(TicketStatus::values())],
            'prioridade' => ['nullable', Rule::in(Prioridade::values())],
            'solicitante_id' => ['nullable', 'integer', 'exists:users,id'],
            'responsavel_id' => ['nullable', 'integer', 'exists:users,id'],
            'active' => ['nullable', 'boolean'],
            'q' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
