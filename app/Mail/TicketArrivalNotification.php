<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketArrivalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build(): self
    {
        return $this->subject("Votre ticket {$this->ticket->ticket_number} approche")
            ->view('emails.ticket_arrival_notification');
    }
}
