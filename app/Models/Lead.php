<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
            'client_id',


        // Identité
        'prenom_nom',
        'nom_global',
        'commentaire',

        // Pipeline
        'chaleur',
        'status',
        'status_relance',
        'enfants_percent',
        'date_statut',

        // Canaux
        'linkedin_status',
        'appel_tel',
        'mp_instagram',
        'follow_insta',
        'com_instagram',
        'formulaire_site',
        'messenger',

        // Entreprise
        'entreprise',
        'fonction',

        // Contact
        'email',
        'tel_fixe',
        'portable',

        // URL
        'url_linkedin',
        'url_maps',
        'url_site',
        'compte_insta',

        // Commercial
        'devis',
    ];

    protected $casts = [
        'follow_insta' => 'boolean',
        'date_statut' => 'date',
    ];

    public function client()
{
    return $this->belongsTo(Client::class);
}

}
