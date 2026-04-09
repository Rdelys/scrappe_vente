<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientImap extends Model
{
    protected $fillable = [
        'client_id',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'folder',
        'last_test_success',
        'last_tested_at',
        'last_sync_at',
    ];

    protected $casts = [
        'password' => 'encrypted',
        'last_test_success' => 'boolean',
        'last_tested_at' => 'datetime',
        'last_sync_at' => 'datetime',
    ];
}
