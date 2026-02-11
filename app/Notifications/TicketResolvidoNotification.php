<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketResolvidoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
    ) {}

    /**
     * Canais de entrega da notificação.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Representação em e-mail da notificação.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->id} Resolvido")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("O ticket **\"{$this->ticket->titulo}\"** foi resolvido.")
            ->line("**Status anterior:** {$this->ticket->statusLogs()->latest()->first()?->from_status}")
            ->line("**Resolvido em:** {$this->ticket->resolved_at->format('d/m/Y H:i')}")
            ->action('Ver Ticket', url(path: "/tickets/{$this->ticket->id}/view"))
            ->line('Obrigado por utilizar nosso sistema de tickets!');
    }

    /**
     * Representação em array da notificação.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->titulo,
            'status' => $this->ticket->status,
            'resolved_at' => $this->ticket->resolved_at,
        ];
    }
}
