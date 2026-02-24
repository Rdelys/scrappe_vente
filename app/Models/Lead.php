<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [

        // Relation
        'client_id',

        // Identité
        'prenom_nom',
        'nom',
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
        'message_form',

        // Entreprise
        'entreprise',
        'categorie',
        'adresse_postale',
        'fonction',

        // Contact
        'email',
        'email_gerant',
        'tel_fixe',
        'portable',

        // URLs
        'url_facebook',
        'url_instagramm',
        'url_linkedin',
        'url_maps',
        'url_site',
        'compte_insta',

        // Google / réputation
        'note',
        'avis',

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