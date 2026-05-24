<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketArrivalNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build(): self
    {
        return $this
            ->subject($this->getSubject())
            ->view('emails.ticket_arrival_notification')
            ->with([
                'ticket' => $this->ticket,
            ]);
    }

    /**
     * Subject centralisé (clean code)
     */
    private function getSubject(): string
    {
        return "Votre ticket {$this->ticket->ticket_number} approche";
    }
}