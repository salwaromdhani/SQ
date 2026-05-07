<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'full_name',
        'email',
        'phone',
        'country',
        'service_id',
        'status',
        'priority',
        'estimated_wait_time',
        'arrival_notified_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'arrival_notified_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function shouldNotifyArrival()
    {
        return $this->status === 'pending'
            && $this->estimated_wait_time <= 5
            && $this->arrival_notified_at === null;
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    // Méthode pour vérifier si le client doit être notifié
    public function shouldNotify()
    {
        return $this->status === 'pending' && $this->estimated_wait_time <= 5;
    }
}