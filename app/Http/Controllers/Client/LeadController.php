<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\PromptIa;

class LeadController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CLIENTS ACCESSIBLES
    |--------------------------------------------------------------------------
    */
    private function getAccessibleClientIds()
    {
        $sessionClient = session('client');

        if (!$sessionClient) {
            return [];
        }

        if ($sessionClient['role'] === 'superadmin') {
            return Client::where('company', $sessionClient['company'])
                ->pluck('id')
                ->toArray();
        }

        return [$sessionClient['id']];
    }

    /*
    |--------------------------------------------------------------------------
    | LISTE
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $clientIds = $this->getAccessibleClientIds();

        if (empty($clientIds)) {
            return redirect()->back()->with('error', 'Session expirée');
        }

        $query = Lead::whereIn('client_id', $clientIds);

        /* ================= FILTRES ================= */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('prenom_nom', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('entreprise', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('portable', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('chaleur')) {
            $query->where('chaleur', $request->chaleur);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_statut', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_statut', '<=', $request->date_to);
        }

        /* ================= STATS ================= */

        $totalLeads   = (clone $query)->count();
        $relanceCount = (clone $query)->where('status', 'À relancer plus tard')->count();
        $rdvPrisCount = (clone $query)->where('status', 'RDV pris')->count();
        $clotureCount = (clone $query)->where('status', 'Clôturé')->count();

                /* ================= RÉCUPÉRER LES NOMS DE SCRAPPING UNIQUES ================= */
        $scrappingNames = Lead::whereIn('client_id', $clientIds)
            ->whereNotNull('nom_global')
            ->where('nom_global', '!=', '')
            ->distinct()
            ->orderBy('nom_global')
            ->pluck('nom_global')
            ->toArray();


        /* ================= PAGINATION ================= */

        $leads = $query->latest()->paginate(20)->withQueryString();

        return view('client.crm.leads', compact(
            'leads',
            'totalLeads',
            'relanceCount',
            'rdvPrisCount',
            'clotureCount',
            'scrappingNames'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $clientId = session('client.id');

        $data = $this->validateLead($request);
        $data['client_id'] = $clientId;
        $data['follow_insta'] = $request->has('follow_insta');

        Lead::create($data);

        return redirect()->route('client.crm.leads')
            ->with('success', 'Lead ajouté avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Lead $lead)
    {
        $clientIds = $this->getAccessibleClientIds();

        if (!in_array($lead->client_id, $clientIds)) {
            abort(403);
        }

        $data = $this->validateLead($request);
        $data['follow_insta'] = $request->has('follow_insta');

        $lead->update($data);

        return redirect()->route('client.crm.leads')
            ->with('success', 'Lead modifié avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Lead $lead)
    {
        $clientIds = $this->getAccessibleClientIds();

        if (!in_array($lead->client_id, $clientIds)) {
            abort(403);
        }

        $lead->delete();

        return redirect()->route('client.crm.leads')
            ->with('success', 'Lead supprimé');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */
    private function validateLead(Request $request)
    {
        return $request->validate([

            // Identité
            'prenom_nom'       => 'required|string|max:255',
            'nom'              => 'nullable|string|max:255',
            'commentaire'      => 'nullable|string',

            // Pipeline
            'chaleur'          => 'nullable|string|max:50',
            'status'           => 'nullable|string|max:255',
            'status_relance'   => 'nullable|string|max:255',
            'enfants_percent'  => 'nullable|string|max:10',
            'date_statut'      => 'nullable|date',

            // Canaux
            'linkedin_status'  => 'nullable|string|max:255',
            'appel_tel'        => 'nullable|string|max:255',
            'mp_instagram'     => 'nullable|string|max:255',
            'com_instagram'    => 'nullable|string|max:255',
            'formulaire_site'  => 'nullable|string|max:255',
            'messenger'        => 'nullable|string|max:255',
            'message_form'     => 'nullable|string|max:255',

            // Entreprise
            'entreprise'       => 'nullable|string|max:255',
            'categorie'        => 'nullable|string|max:255',
            'adresse_postale'  => 'nullable|string|max:255',
            'fonction'         => 'nullable|string|max:255',

            // Contact
            'email'            => 'nullable|email|max:255',
            'email_gerant'     => 'nullable|email|max:255',
            'tel_fixe'         => 'nullable|string|max:50',
            'portable'         => 'nullable|string|max:50',

            // URLs
            'url_facebook'     => 'nullable|string|max:255',
            'url_instagramm'   => 'nullable|string|max:255',
            'url_linkedin'     => 'nullable|string|max:255',
            'url_maps'         => 'nullable|string|max:255',
            'url_site'         => 'nullable|string|max:255',
            'compte_insta'     => 'nullable|string|max:255',

            // Commercial
            'devis'            => 'nullable|string|max:50',
        ]);
    }


   public function exportExcel(Request $request)
{
    $clientIds = $this->getAccessibleClientIds();

    if (empty($clientIds)) {
        abort(403);
    }

    $query = Lead::whereIn('client_id', $clientIds);

    /* ================= FILTRES ================= */

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('prenom_nom', 'like', "%{$search}%")
              ->orWhere('nom_global', 'like', "%{$search}%")
              ->orWhere('nom', 'like', "%{$search}%")
              ->orWhere('entreprise', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('portable', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('chaleur')) {
        $query->where('chaleur', $request->chaleur);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('date_statut', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('date_statut', '<=', $request->date_to);
    }

    $leads = $query->orderByDesc('created_at')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    /* ================= HEADERS ================= */

    $headers = [
        'Nom Global',
        'Prénom',
        'Nom',
        'Commentaire',
        'Chaleur',
        'Status',
        'Status Relance',
        'Lead à jour de ses infos',
        'Date Statut',
        'LinkedIn',
        'Téléphone',
        'MP Instagram',
        'Follow Insta',
        'Com Insta',
        'Formulaire',
        'Messenger',
        'Message Formulaire',
        'Entreprise',
        'Catégorie',
        'Adresse',
        'Fonction',
        'Email Entreprise',
        'Email Gérant',
        'Tel Fixe',
        'Portable',
        'URL Facebook',
        'URL Instagram',
        'URL LinkedIn',
        'URL Maps',
        'URL Site',
        'Compte Insta',
        'Devis'
    ];

    foreach ($headers as $index => $header) {
    $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
    $sheet->setCellValue($columnLetter . '1', $header);
}

    /* ================= DONNÉES ================= */

    $row = 2;

    foreach ($leads as $lead) {

        $data = [
            $lead->nom_global,
            $lead->prenom_nom,
            $lead->nom,
            $lead->commentaire,
            $lead->chaleur,
            $lead->status,
            $lead->status_relance,
            $lead->enfants_percent,
            $lead->date_statut,
            $lead->linkedin_status,
            $lead->appel_tel,
            $lead->mp_instagram,
            $lead->follow_insta ? 'Oui' : 'Non',
            $lead->com_instagram,
            $lead->formulaire_site,
            $lead->messenger,
            $lead->message_form,
            $lead->entreprise,
            $lead->categorie,
            $lead->adresse_postale,
            $lead->fonction,
            $lead->email,
            $lead->email_gerant,
            $lead->tel_fixe,
            $lead->portable,
            $lead->url_facebook,
            $lead->url_instagramm,
            $lead->url_linkedin,
            $lead->url_maps,
            $lead->url_site,
            $lead->compte_insta,
            $lead->devis,
        ];

        foreach ($data as $colIndex => $value) {
    $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
    $sheet->setCellValue($columnLetter . $row, $value);
}

        $row++;
    }

    /* ================= AUTO SIZE ================= */

    $highestColumn = $sheet->getHighestColumn();
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
    }

    /* ================= DOWNLOAD ================= */

    $fileName = 'leads_' . now()->format('Y-m-d_H-i') . '.xlsx';
    $filePath = storage_path('app/' . $fileName);

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

public function exportSingleExcel(Lead $lead)
{
    $clientIds = $this->getAccessibleClientIds();

    if (!in_array($lead->client_id, $clientIds)) {
        abort(403);
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    /*
    |--------------------------------------------------------------------------
    | HEADERS (IDENTIQUE EXPORT GLOBAL)
    |--------------------------------------------------------------------------
    */

    $headers = [
        'Nom Global',
        'Prénom',
        'Nom',
        'Commentaire',
        'Chaleur',
        'Status',
        'Status Relance',
        'Lead à jour de ses infos',
        'Date Statut',
        'LinkedIn',
        'Téléphone',
        'MP Instagram',
        'Follow Insta',
        'Com Insta',
        'Formulaire',
        'Messenger',
        'Message Formulaire',
        'Entreprise',
        'Catégorie',
        'Adresse',
        'Fonction',
        'Email Entreprise',
        'Email Gérant',
        'Tel Fixe',
        'Portable',
        'URL Facebook',
        'URL Instagram',
        'URL LinkedIn',
        'URL Maps',
        'URL Site',
        'Compte Insta',
        'Devis'
    ];

    foreach ($headers as $index => $header) {
    $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
    $sheet->setCellValue($columnLetter . '1', $header);
}

    /*
    |--------------------------------------------------------------------------
    | DONNÉES
    |--------------------------------------------------------------------------
    */

    $row = 2;

    $data = [
        $lead->nom_global,
        $lead->prenom_nom,
        $lead->nom,
        $lead->commentaire,
        $lead->chaleur,
        $lead->status,
        $lead->status_relance,
        $lead->enfants_percent,
        $lead->date_statut,
        $lead->linkedin_status,
        $lead->appel_tel,
        $lead->mp_instagram,
        $lead->follow_insta ? 'Oui' : 'Non',
        $lead->com_instagram,
        $lead->formulaire_site,
        $lead->messenger,
        $lead->message_form,
        $lead->entreprise,
        $lead->categorie,
        $lead->adresse_postale,
        $lead->fonction,
        $lead->email,
        $lead->email_gerant,
        $lead->tel_fixe,
        $lead->portable,
        $lead->url_facebook,
        $lead->url_instagramm,
        $lead->url_linkedin,
        $lead->url_maps,
        $lead->url_site,
        $lead->compte_insta,
        $lead->devis,
    ];

    foreach ($data as $colIndex => $value) {
    $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
    $sheet->setCellValue($columnLetter . $row, $value);
}

    /*
    |--------------------------------------------------------------------------
    | AUTO SIZE PRO
    |--------------------------------------------------------------------------
    */

    $highestColumn = $sheet->getHighestColumn();
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | STREAM DOWNLOAD
    |--------------------------------------------------------------------------
    */

    $fileName = 'lead_'.$lead->id.'_'.now()->format('Y-m-d_H-i').'.xlsx';

    return response()->streamDownload(function () use ($spreadsheet) {
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }, $fileName);
}


public function generateMails(Lead $lead)
{
    $clientIds = $this->getAccessibleClientIds();

    if (!in_array($lead->client_id, $clientIds)) {
        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | RÉCUPÉRER LE PROMPT SELON LE GROUPE
    |--------------------------------------------------------------------------
    */

    $promptModel = PromptIa::where('nom_groupe', $lead->nom_global)->first();

    if (!$promptModel) {
        return response()->json([
            'content' => "Aucun prompt IA configuré pour ce groupe."
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PROMPT UTILISATEUR
    |--------------------------------------------------------------------------
    */

    $basePrompt = $promptModel->prompt;

    /*
    |--------------------------------------------------------------------------
    | INJECTER LES VARIABLES ENTREPRISE
    |--------------------------------------------------------------------------
    */

    $prompt = $basePrompt . "

L'entreprise qui envoie les emails est : DELEGG.

DELEGG contacte un prospect pour proposer ses services.

Informations du prospect :

Entreprise : {$lead->entreprise}
Prénom : {$lead->prenom_nom}
Nom : {$lead->nom}
Fonction : {$lead->fonction}
Catégorie : {$lead->categorie}
Chaleur : {$lead->chaleur}
Commentaire interne : {$lead->commentaire}

Génère 5 emails de prospection différents envoyés par DELEGG à ce prospect.

Chaque email doit :
- être professionnel
- être personnalisé selon l'entreprise du prospect
- faire maximum 150 mots
- contenir un objet
- avoir un ton persuasif
- proposer une prise de contact ou un rendez-vous

Format attendu :

Email 1
Objet:
Contenu:

Email 2
Objet:
Contenu:

Email 3
Objet:
Contenu:

Email 4
Objet:
Contenu:

Email 5
Objet:
Contenu:
";
    /*
    |--------------------------------------------------------------------------
    | OPENAI
    |--------------------------------------------------------------------------
    */

    $response = OpenAI::chat()->create([
        'model' => 'gpt-4o-mini',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'Tu es un expert en copywriting B2B.'
            ],
            [
                'role' => 'user',
                'content' => $prompt
            ],
        ],
        'temperature' => 0.9,
    ]);

    return response()->json([
        'content' => $response->choices[0]->message->content
    ]);
}

public function inlineUpdate(Request $request, Lead $lead)
{
    $clientIds = $this->getAccessibleClientIds();

    if (!in_array($lead->client_id, $clientIds)) {
        return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
    }

    $field = $request->input('field');
    $value = $request->input('value');

    // Valider le champ
    $allowedFields = [
        'entreprise', 'prenom_nom', 'nom', 'adresse_postale', 'commentaire',
        'chaleur', 'status', 'url_linkedin', 'url_facebook', 'url_instagramm',
        'appel_tel', 'mp_instagram', 'linkedin_status', 'messenger', 
        'message_form', 'categorie', 'status_relance', 'date_statut',
        'enfants_percent', 'devis', 'follow_insta', 'com_instagram',
        'formulaire_site', 'fonction', 'email', 'email_gerant', 'tel_fixe',
        'portable', 'url_site', 'compte_insta', 'note', 'avis', 'nom_global',
        'url_maps'
    ];

    if (!in_array($field, $allowedFields)) {
        return response()->json(['success' => false, 'message' => 'Champ non autorisé'], 400);
    }

    // Traitement spécial pour les checkboxes
    if ($value === 'Oui') $value = 1;
    if ($value === 'Non') $value = 0;

    try {
        $lead->$field = $value;
        $lead->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
}
