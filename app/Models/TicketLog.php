<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketLog extends Model
{
    protected $fillable = ['ticket_id', 'action', 'old_value', 'new_value', 'comment'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getDetailsAttribute(): ?string
    {
        if ($this->comment) {
            return $this->comment;
        }

        $parts = [];

        if ($this->old_value) {
            $parts[] = "Avant : {$this->old_value}";
        }

        if ($this->new_value) {
            $parts[] = "Après : {$this->new_value}";
        }

        return $parts ? implode(' / ', $parts) : null;
    }
}