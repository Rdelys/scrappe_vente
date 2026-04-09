<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromptIa;
use App\Models\Lead;
use App\Models\Client;

class PromptIaController extends Controller
{

    public function index()
{
    $client = session('client');

    $clientId = $client['id'];
    $company  = $client['company'];
    $role     = $client['role'];

    if ($role !== 'superadmin') {

        // tous les groupes leads
        $leadGroupes = Lead::where('client_id', $clientId)
            ->whereNotNull('nom_global')
            ->where('nom_global','!=','')
            ->select('nom_global')
            ->distinct()
            ->orderBy('nom_global')
            ->pluck('nom_global');

        // groupes déjà utilisés
        $usedGroupes = PromptIa::where('client_id',$clientId)
            ->pluck('nom_groupe');

        // pour création
        $groupesAdd = $leadGroupes->diff($usedGroupes);

        // pour édition
        $groupesEdit = $leadGroupes;

        $prompts = PromptIa::where('client_id',$clientId)
            ->latest()
            ->get();
    }

    else {

        $clientsCompany = Client::where('company',$company)->pluck('id');

        $leadGroupes = Lead::whereIn('client_id',$clientsCompany)
            ->whereNotNull('nom_global')
            ->where('nom_global','!=','')
            ->select('nom_global')
            ->distinct()
            ->orderBy('nom_global')
            ->pluck('nom_global');

        $usedGroupes = PromptIa::whereIn('client_id',$clientsCompany)
            ->pluck('nom_groupe');

        $groupesAdd = $leadGroupes->diff($usedGroupes);

        $groupesEdit = $leadGroupes;

        $prompts = PromptIa::whereIn('client_id',$clientsCompany)
            ->latest()
            ->get();
    }

    return view('client.prompt-ia', compact(
        'prompts',
        'groupesAdd',
        'groupesEdit'
    ));
}


    public function store(Request $request)
    {
        $request->validate([
            'nom_groupe'=>'required',
            'prompt'=>'required'
        ]);

        $client = session('client');

        PromptIa::create([
            'client_id'=>$client['id'],
            'nom_groupe'=>$request->nom_groupe,
            'prompt'=>$request->prompt
        ]);

        return back()->with('success','Prompt enregistré');
    }


    public function update(Request $request,$id)
    {
        $prompt = PromptIa::findOrFail($id);

        $prompt->update([
            'nom_groupe'=>$request->nom_groupe,
            'prompt'=>$request->prompt
        ]);

        return back()->with('success','Prompt modifié');
    }


    public function destroy($id)
    {
        PromptIa::findOrFail($id)->delete();

        return back()->with('success','Prompt supprimé');
    }

}