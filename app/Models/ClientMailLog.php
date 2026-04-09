<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientMailLog extends Model
{
    protected $fillable = [
        'client_id',
        'to',
        'subject',
        'body',
        'status',
        'error_message',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}