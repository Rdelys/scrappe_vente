<?php
namespace App\Services;

use Webklex\IMAP\Facades\Client;
use App\Models\ClientImap;

class ClientImapService
{
    public static function connect(ClientImap $imap)
    {
        $client = Client::make([
            'host' => $imap->host,
            'port' => $imap->port,
            'encryption' => $imap->encryption,
            'validate_cert' => true,
            'username' => $imap->username,
            'password' => decrypt($imap->password),
            'protocol' => 'imap'
        ]);

        $client->connect();

        return $client;
    }
}