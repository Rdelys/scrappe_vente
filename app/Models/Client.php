<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'company',
        'password',
        'status',
        'role',
    ];


    protected $hidden = [
        'password',
    ];

    public function licences()
    {
        return $this->hasMany(Licence::class);
    }

    public function leads()
{
    return $this->hasMany(Lead::class);
}

public function smtp()
{
    return $this->hasOne(ClientSmtp::class);
}

public function mailLogs()
{
    return $this->hasMany(ClientMailLog::class);
}
}
