<?php

namespace App\Http\Requests;

use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Ticket;

class UpdateTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');

        if (is_numeric($ticket)) {
            $ticket = Ticket::find($ticket);
        }

        if ($ticket instanceof Ticket) {
            return $this->user()->can('updateStatus', $ticket);
        }

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
