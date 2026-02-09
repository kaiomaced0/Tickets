<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'in:ABERTO,EM_ANDAMENTO,RESOLVIDO'],
            'prioridade' => ['nullable', 'in:BAIXA,MEDIA,ALTA'],
            'solicitante_id' => ['nullable', 'integer', 'exists:users,id'],
            'responsavel_id' => ['nullable', 'integer', 'exists:users,id'],
            'active' => ['nullable', 'boolean'],
            'q' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
