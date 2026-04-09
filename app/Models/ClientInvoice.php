<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientInvoice extends Model
{

    protected $table = 'clients_invoice';

    protected $fillable = [

        'type',

        'company_name',
        'siret',
        'tva',

        'first_name',
        'last_name',

        'email',
        'phone',

        'contact_firstname',
        'contact_lastname',
        'contact_function',
        'contact_email',
        'contact_phone',

        'address',
        'address_complement',
        'postal_code',
        'city',
        'country',

        'iban',
        'bic',

        'include_address',

        'notes'
    ];

}
