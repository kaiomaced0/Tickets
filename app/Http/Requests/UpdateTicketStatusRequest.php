<?php

namespace App\Http\Requests;

use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => TicketStatus::tryFromMixed($this->input('status'))?->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(TicketStatus::values())],
        ];
    }
}
