<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\ClientSmtp;

class ClientMailService
{
    public static function configure(ClientSmtp $smtp)
    {
        Config::set('mail.mailers.client', [
            'transport' => 'smtp',
            'host' => $smtp->host,
            'port' => $smtp->port,
            'encryption' => $smtp->encryption,
            'username' => $smtp->username,
            'password' => decrypt($smtp->password),
        ]);

        Config::set('mail.from.address', $smtp->username);
        Config::set('mail.from.name', $smtp->from_name ?? 'Mail System');
    }
}