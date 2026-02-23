<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LeadController extends Controller
{
    private function getAccessibleClientIds()
{
    $sessionClient = session('client');

    if (!$sessionClient) {
        return [];
    }

    // Superadmin → tous les clients de la même company
    if ($sessionClient['role'] === 'superadmin') {
        return \App\Models\Client::where('company', $sessionClient['company'])
            ->pluck('id')
            ->toArray();
    }

    // Sinon → uniquement lui
    return [$sessionClient['id']];
}
    /*
    |--------------------------------------------------------------------------
    | LISTE DES LEADS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
{
    $clientIds = $this->getAccessibleClientIds();

if (empty($clientIds)) {
    return redirect()->back()->with('error', 'Session expirée');
}

$baseQuery = Lead::whereIn('client_id', $clientIds);
    /*
    |--------------------------------------------------------------------------
    | FILTRES
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {
        $search = $request->search;

        $baseQuery->where(function ($q) use ($search) {
            $q->where('prenom_nom', 'like', "%{$search}%")
              ->orWhere('nom_global', 'like', "%{$search}%")
              ->orWhere('entreprise', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('portable', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $baseQuery->where('status', $request->status);
    }

    if ($request->filled('chaleur')) {
        $baseQuery->where('chaleur', $request->chaleur);
    }

    if ($request->filled('date_from')) {
        $baseQuery->whereDate('date_statut', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $baseQuery->whereDate('date_statut', '<=', $request->date_to);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 STATS (SUR LES DONNÉES FILTRÉES)
    |--------------------------------------------------------------------------
    */

    $statsQuery = clone $baseQuery;

    $totalLeads = $statsQuery->count();

    $relanceCount = (clone $baseQuery)
        ->where('status', 'À relancer plus tard')
        ->count();

    $rdvPrisCount = (clone $baseQuery)
        ->where('status', 'RDV pris')
        ->count();

    $clotureCount = (clone $baseQuery)
        ->where('status', 'Clôturé')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | LISTE PAGINÉE
    |--------------------------------------------------------------------------
    */

    $leads = $baseQuery
        ->latest()
        ->paginate(20)
        ->withQueryString();

    return view('client.crm.leads', compact(
        'leads',
        'totalLeads',
        'relanceCount',
        'rdvPrisCount',
        'clotureCount'
    ));
}


    /*
    |--------------------------------------------------------------------------
    | AJOUTER UN LEAD
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $clientId = session('client.id');

        $data = $this->validateLead($request);

        $data['client_id'] = $clientId;
        $data['follow_insta'] = $request->has('follow_insta');

        Lead::create($data);

        return redirect()
            ->route('client.crm.leads')
            ->with('success', 'Lead ajouté avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | MODIFIER UN LEAD
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Lead $lead)
    {
        $clientId = session('client.id');

        // Sécurité
        if ($lead->client_id !== $clientId) {
            abort(403);
        }

        $data = $this->validateLead($request);

        $data['follow_insta'] = $request->has('follow_insta');

        $lead->update($data);

        return redirect()
            ->route('client.crm.leads')
            ->with('success', 'Lead modifié avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER
    |--------------------------------------------------------------------------
    */
    public function destroy(Lead $lead)
    {
        $clientId = session('client.id');

        if ($lead->client_id !== $clientId) {
            abort(403);
        }

        $lead->delete();

        return redirect()
            ->route('client.crm.leads')
            ->with('success', 'Lead supprimé');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION CENTRALISÉE
    |--------------------------------------------------------------------------
    */
    private function validateLead(Request $request)
    {
        return $request->validate([

            // Identité
            'prenom_nom'       => 'required|string|max:255',
            'nom_global'       => 'nullable|string|max:255',
            'commentaire'      => 'nullable|string',

            // Pipeline
            'chaleur'          => 'nullable|string|max:50',
            'status'           => 'nullable|string|max:255',
            'status_relance'   => 'nullable|string|max:255',
            'enfants_percent'  => 'nullable|string|max:10',
            'date_statut'      => 'nullable|date',

            // Canaux
            'linkedin_status'  => 'nullable|string|max:255',
            'suivi_mail'       => 'nullable|string|max:255',
            'suivi_whatsapp'   => 'nullable|string|max:255',
            'appel_tel'        => 'nullable|string|max:255',
            'mp_instagram'     => 'nullable|string|max:255',
            'com_instagram'    => 'nullable|string|max:255',
            'formulaire_site'  => 'nullable|string|max:255',
            'messenger'        => 'nullable|string|max:255',

            // Entreprise
            'entreprise'       => 'nullable|string|max:255',
            'fonction'         => 'nullable|string|max:255',

            // Contact
            'email'            => 'nullable|email|max:255',
            'tel_fixe'         => 'nullable|string|max:50',
            'portable'         => 'nullable|string|max:50',

            // URL
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
    // 🔥 Reproduire filtres
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('prenom_nom', 'like', "%{$search}%")
              ->orWhere('nom_global', 'like', "%{$search}%")
              ->orWhere('entreprise', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
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

    $headers = [
        'Nom Global',
        'Prénom Nom',
        'Commentaire',
        'Chaleur',
        'Status',
        'Status Relance',
        'Lead à jour de ses infos',
        'Date Statut',
        'LinkedIn',
        'Suivi Mail',
        'WhatsApp',
        'Téléphone',
        'MP Instagram',
        'Follow Insta',
        'Com Insta',
        'Formulaire',
        'Messenger',
        'Entreprise',
        'Fonction',
        'Email',
        'Tel Fixe',
        'Portable',
        'URL LinkedIn',
        'URL Maps',
        'URL Site',
        'Compte Insta',
        'Devis'
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    $row = 2;

    foreach ($leads as $lead) {

        $sheet->setCellValue('A'.$row, $lead->nom_global);
        $sheet->setCellValue('B'.$row, $lead->prenom_nom);
        $sheet->setCellValue('C'.$row, $lead->commentaire);
        $sheet->setCellValue('D'.$row, $lead->chaleur);
        $sheet->setCellValue('E'.$row, $lead->status);
        $sheet->setCellValue('F'.$row, $lead->status_relance);
        $sheet->setCellValue('G'.$row, $lead->enfants_percent);
        $sheet->setCellValue('H'.$row, $lead->date_statut);
        $sheet->setCellValue('I'.$row, $lead->linkedin_status);
        $sheet->setCellValue('J'.$row, $lead->suivi_mail);
        $sheet->setCellValue('K'.$row, $lead->suivi_whatsapp);
        $sheet->setCellValue('L'.$row, $lead->appel_tel);
        $sheet->setCellValue('M'.$row, $lead->mp_instagram);
        $sheet->setCellValue('N'.$row, $lead->follow_insta ? 'Oui' : 'Non');
        $sheet->setCellValue('O'.$row, $lead->com_instagram);
        $sheet->setCellValue('P'.$row, $lead->formulaire_site);
        $sheet->setCellValue('Q'.$row, $lead->messenger);
        $sheet->setCellValue('R'.$row, $lead->entreprise);
        $sheet->setCellValue('S'.$row, $lead->fonction);
        $sheet->setCellValue('T'.$row, $lead->email);
        $sheet->setCellValue('U'.$row, $lead->tel_fixe);
        $sheet->setCellValue('V'.$row, $lead->portable);
        $sheet->setCellValue('W'.$row, $lead->url_linkedin);
        $sheet->setCellValue('X'.$row, $lead->url_maps);
        $sheet->setCellValue('Y'.$row, $lead->url_site);
        $sheet->setCellValue('Z'.$row, $lead->compte_insta);
        $sheet->setCellValue('AA'.$row, $lead->devis);

        $row++;
    }

    $highestColumn = $sheet->getHighestColumn();

for ($col = 'A'; $col !== $highestColumn; $col++) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getColumnDimension($highestColumn)->setAutoSize(true);


    $fileName = 'leads_'.now()->format('Y-m-d_H-i').'.xlsx';
    $filePath = storage_path('app/' . $fileName);

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

public function exportSingleExcel(Lead $lead)
{
    $clientId = session('client.id');

    if ($lead->client_id != $clientId) {
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
        'Prénom Nom',
        'Commentaire',
        'Chaleur',
        'Status',
        'Status Relance',
        'Lead à jour de ses infos',
        'Date Statut',
        'LinkedIn',
        'Suivi Mail',
        'WhatsApp',
        'Téléphone',
        'MP Instagram',
        'Follow Insta',
        'Com Insta',
        'Formulaire',
        'Messenger',
        'Entreprise',
        'Fonction',
        'Email',
        'Tel Fixe',
        'Portable',
        'URL LinkedIn',
        'URL Maps',
        'URL Site',
        'Compte Insta',
        'Devis'
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    /*
    |--------------------------------------------------------------------------
    | DONNÉES (UNE SEULE LIGNE)
    |--------------------------------------------------------------------------
    */

    $row = 2;

    $sheet->setCellValue('A'.$row, $lead->nom_global);
    $sheet->setCellValue('B'.$row, $lead->prenom_nom);
    $sheet->setCellValue('C'.$row, $lead->commentaire);
    $sheet->setCellValue('D'.$row, $lead->chaleur);
    $sheet->setCellValue('E'.$row, $lead->status);
    $sheet->setCellValue('F'.$row, $lead->status_relance);
    $sheet->setCellValue('G'.$row, $lead->enfants_percent);
    $sheet->setCellValue('H'.$row, $lead->date_statut);
    $sheet->setCellValue('I'.$row, $lead->linkedin_status);
    $sheet->setCellValue('J'.$row, $lead->suivi_mail);
    $sheet->setCellValue('K'.$row, $lead->suivi_whatsapp);
    $sheet->setCellValue('L'.$row, $lead->appel_tel);
    $sheet->setCellValue('M'.$row, $lead->mp_instagram);
    $sheet->setCellValue('N'.$row, $lead->follow_insta ? 'Oui' : 'Non');
    $sheet->setCellValue('O'.$row, $lead->com_instagram);
    $sheet->setCellValue('P'.$row, $lead->formulaire_site);
    $sheet->setCellValue('Q'.$row, $lead->messenger);
    $sheet->setCellValue('R'.$row, $lead->entreprise);
    $sheet->setCellValue('S'.$row, $lead->fonction);
    $sheet->setCellValue('T'.$row, $lead->email);
    $sheet->setCellValue('U'.$row, $lead->tel_fixe);
    $sheet->setCellValue('V'.$row, $lead->portable);
    $sheet->setCellValue('W'.$row, $lead->url_linkedin);
    $sheet->setCellValue('X'.$row, $lead->url_maps);
    $sheet->setCellValue('Y'.$row, $lead->url_site);
    $sheet->setCellValue('Z'.$row, $lead->compte_insta);
    $sheet->setCellValue('AA'.$row, $lead->devis);

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
    | STREAM DOWNLOAD (PLUS PROPRE)
    |--------------------------------------------------------------------------
    */

    $fileName = 'lead_'.$lead->id.'_'.now()->format('Y-m-d_H-i').'.xlsx';

    return response()->streamDownload(function () use ($spreadsheet) {
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }, $fileName);
}


}
