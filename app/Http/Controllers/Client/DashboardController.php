<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\GooglePlace;
use App\Models\Client;

class DashboardController extends Controller
{
    private function getAccessibleClientIds()
    {
        $sessionClient = session('client');

        if (!$sessionClient) {
            return [];
        }

        // Superadmin voit toute la company
        if ($sessionClient['role'] === 'superadmin') {
            return Client::where('company', $sessionClient['company'])
                ->pluck('id')
                ->toArray();
        }

        // Sinon seulement lui
        return [$sessionClient['id']];
    }

    /**
     * Calcule le pourcentage de complétude d'un lead
     */
    private function calculateCompletionPercentage($lead)
    {
        // Liste des champs à vérifier pour la complétude
        $fields = [
            // Identité (4 points)
            'prenom_nom' => 4,
            'nom_global' => 4,
            'commentaire' => 4,
            
            // Pipeline (4 points)
            'chaleur' => 4,
            'status' => 4,
            'status_relance' => 4,
            
            // Canaux (7 points - 1 point par canal)
            'linkedin_status' => 1,
            'appel_tel' => 1,
            'mp_instagram' => 1,
            'follow_insta' => 1,
            'com_instagram' => 1,
            'formulaire_site' => 1,
            'messenger' => 1,
            
            // Entreprise (2 points)
            'entreprise' => 2,
            'fonction' => 2,
            
            // Contact (6 points - 2 points par champ de contact)
            'email' => 2,
            'tel_fixe' => 2,
            'portable' => 2,
            
            // URL (4 points - 1 point par URL)
            'url_linkedin' => 1,
            'url_maps' => 1,
            'url_site' => 1,
            'compte_insta' => 1,
            
            // Commercial (2 points)
            'devis' => 2,
        ];

        $totalPoints = array_sum($fields);
        $earnedPoints = 0;

        foreach ($fields as $field => $points) {
            $value = $lead->$field;
            
            // Vérifier si le champ est rempli (non null, non vide, et pas "À renseigner" pour certains champs)
            if (!is_null($value) && $value !== '' && $value !== 'À renseigner') {
                // Pour les champs boolean, vérifier qu'ils sont true
                if (in_array($field, ['follow_insta']) && !$value) {
                    continue;
                }
                $earnedPoints += $points;
            }
        }

        return round(($earnedPoints / $totalPoints) * 100);
    }

    /**
     * Catégorise un pourcentage dans les tranches prédéfinies
     */
    private function categorizePercentage($percentage)
    {
        if ($percentage <= 20) return '0-20%';
        if ($percentage <= 40) return '21-40%';
        if ($percentage <= 60) return '41-60%';
        if ($percentage <= 80) return '61-80%';
        return '81-100%';
    }

    public function index()
    {
        $sessionClient = session('client');
        
        if (!$sessionClient) {
            abort(403);
        }

        $clientIds = $this->getAccessibleClientIds();

        if (empty($clientIds)) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | GOOGLE SCRAPER
        |--------------------------------------------------------------------------
        */

        $totalScraped = GooglePlace::whereIn('client_id', $clientIds)->count();

        /*
        |--------------------------------------------------------------------------
        | LEADS
        |--------------------------------------------------------------------------
        */

        $leads = Lead::whereIn('client_id', $clientIds);

        $totalLeads = $leads->count();

        $leadsChauds = (clone $leads)
            ->where('chaleur', 'Chaud')
            ->count();

        $rdvPris = (clone $leads)
            ->where('status_relance', 'RDV pris')
            ->count();

        $ventes = (clone $leads)
            ->where('status', 'Vendu')
            ->count();

        $tauxConversion = $totalLeads > 0
            ? round(($ventes / $totalLeads) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | DISTRIBUTION CHALEUR
        |--------------------------------------------------------------------------
        */

        $froid = (clone $leads)->where('chaleur', 'Froid')->count();
        $tiede = (clone $leads)->where('chaleur', 'Tiède')->count();
        $chaud = (clone $leads)->where('chaleur', 'Chaud')->count();
        $mort = (clone $leads)->where('status', 'Mort')->count();

        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES DE COMPLÉTUDE GLOBALES
        |--------------------------------------------------------------------------
        */

        // Récupérer tous les leads pour calculer les statistiques de complétude
        $allLeads = Lead::whereIn('client_id', $clientIds)->get();
        
        $completionStats = [
            '0-20%' => 0,
            '21-40%' => 0,
            '41-60%' => 0,
            '61-80%' => 0,
            '81-100%' => 0,
        ];

        $totalCompletionPercentage = 0;

        foreach ($allLeads as $lead) {
            $percentage = $this->calculateCompletionPercentage($lead);
            $totalCompletionPercentage += $percentage;

            $category = $this->categorizePercentage($percentage);
            $completionStats[$category]++;
        }

        $averageCompletion = $totalLeads > 0 
            ? round($totalCompletionPercentage / $totalLeads) 
            : 0;

        /*
        |--------------------------------------------------------------------------
        | PERFORMANCES PAR CLIENT AVEC RÉPARTITION DES POURCENTAGES
        |--------------------------------------------------------------------------
        */

        // Récupérer tous les clients de la même company sauf le superadmin
        $clients = Client::where('company', $sessionClient['company'])
            ->where('role', '!=', 'superadmin')
            ->get();

        $clientsPerformance = [];

        foreach ($clients as $client) {
            // Récupérer tous les leads de ce client
            $clientLeads = Lead::where('client_id', $client->id)->get();
            
            // Nombre total de leads
            $leadsCount = $clientLeads->count();
            
            // Nombre de scrappings (Google Places) pour ce client
            $scrapingCount = GooglePlace::where('client_id', $client->id)->count();
            
            // Calcul de la répartition par pourcentage
            $percentageBreakdown = [
                '0-20%' => 0,
                '21-40%' => 0,
                '41-60%' => 0,
                '61-80%' => 0,
                '81-100%' => 0,
            ];
            
            foreach ($clientLeads as $lead) {
                $percentage = $this->calculateCompletionPercentage($lead);
                $category = $this->categorizePercentage($percentage);
                $percentageBreakdown[$category]++;
            }
            
            // Nom de l'utilisateur
            $userName = trim($client->first_name . ' ' . $client->last_name);
            if (empty($userName)) {
                $userName = $client->email;
            }

            $clientsPerformance[] = [
                'id' => $client->id,
                'name' => $userName,
                'email' => $client->email,
                'role' => $client->role,
                'leads_count' => $leadsCount,
                'scraping_count' => $scrapingCount,
                'percentage_breakdown' => $percentageBreakdown,
            ];
        }

        // Trier par nombre de leads décroissant
        usort($clientsPerformance, function($a, $b) {
            return $b['leads_count'] - $a['leads_count'];
        });

        return view('client.crm.dashboard', compact(
            'totalScraped',
            'totalLeads',
            'leadsChauds',
            'rdvPris',
            'ventes',
            'tauxConversion',
            'froid',
            'tiede',
            'chaud',
            'mort',
            'completionStats',
            'averageCompletion',
            'clientsPerformance',
            'sessionClient'
        ));
    }
}