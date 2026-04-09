<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientInvoice;

class ClientInvoiceController extends Controller
{

    // LISTE
    public function index()
    {
        $clients = ClientInvoice::latest()->get();

        return view('client.invoice.clients', compact('clients'));
    }


    // STORE
    public function store(Request $request)
    {

        $data = $request->validate([
    'type'=>'required',

    'company_name'=>'nullable',
    'siret'=>'nullable',
    'tva'=>'nullable',

    'first_name'=>'nullable',
    'last_name'=>'nullable',

    'email'=>'required|email',
    'phone'=>'nullable',

    'contact_firstname'=>'nullable|array',
    'contact_lastname'=>'nullable|array',
    'contact_function'=>'nullable|array',
    'contact_email'=>'nullable|array',
    'contact_phone'=>'nullable|array',

    'address'=>'required',
    'address_complement'=>'nullable',
    'postal_code'=>'required',
    'city'=>'required',
    'country'=>'required',

    'iban'=>'nullable',
    'bic'=>'nullable',

    'include_address'=>'nullable',

    'notes'=>'nullable'
]);

$data['contact_firstname'] = json_encode($request->contact_firstname);
$data['contact_lastname'] = json_encode($request->contact_lastname);
$data['contact_function'] = json_encode($request->contact_function);
$data['contact_email'] = json_encode($request->contact_email);
$data['contact_phone'] = json_encode($request->contact_phone);

ClientInvoice::create($data);

        return back()->with('success','Client ajouté');
    }

// Récupérer les données d'un client pour l'édition
public function edit($id)
{
    $client = ClientInvoice::findOrFail($id);
    return response()->json($client);
}
    // UPDATE
    public function update(Request $request,$id)
    {

        $client = ClientInvoice::findOrFail($id);

$data = $request->validate([

'type'=>'required',

'company_name'=>'nullable',
'siret'=>'nullable',
'tva'=>'nullable',

'first_name'=>'nullable',
'last_name'=>'nullable',

'email'=>'required|email',
'phone'=>'nullable',

'contact_firstname'=>'nullable|array',
'contact_lastname'=>'nullable|array',
'contact_function'=>'nullable|array',
'contact_email'=>'nullable|array',
'contact_phone'=>'nullable|array',

'address'=>'required',
'address_complement'=>'nullable',
'postal_code'=>'required',
'city'=>'required',
'country'=>'required',

'iban'=>'nullable',
'bic'=>'nullable',

'include_address'=>'nullable',
'notes'=>'nullable'
]);

$data['contact_firstname'] = json_encode($request->contact_firstname);
$data['contact_lastname'] = json_encode($request->contact_lastname);
$data['contact_function'] = json_encode($request->contact_function);
$data['contact_email'] = json_encode($request->contact_email);
$data['contact_phone'] = json_encode($request->contact_phone);

$client->update($data);

        return back()->with('success','Client modifié');
    }


    // DELETE
    public function destroy($id)
    {

        $client = ClientInvoice::findOrFail($id);

        $client->delete();

        return back()->with('success','Client supprimé');
    }

}