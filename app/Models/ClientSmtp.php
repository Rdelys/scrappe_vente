<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSmtp extends Model
{
    protected $fillable = [
        'client_id',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_name',
        'is_active',
            'last_test_success',      // ✅ AJOUTER
    'last_tested_at',  
    ];

    protected $casts = [
    'password' => 'encrypted',
    'last_test_success' => 'boolean',
    'last_tested_at' => 'datetime',
];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}