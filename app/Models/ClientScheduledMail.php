<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientScheduledMail extends Model
{
    protected $fillable = [
        'client_id',
        'to',
        'subject',
        'body',
        'scheduled_at',
        'status',
        'error_message',
        'sent_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}