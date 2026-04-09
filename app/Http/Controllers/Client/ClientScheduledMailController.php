<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientScheduledMail;
use App\Models\ClientSmtp;

class ClientScheduledMailController extends Controller
{
    public function index()
{
    $clientId = session('client.id');

    $mails = ClientScheduledMail::where('client_id', $clientId)
        ->latest()
        ->get();

    $smtp = ClientSmtp::where('client_id', $clientId)->first();

    return view('client.mails.programmes', [
        'mails' => $mails,
        'serverTimezone' => config('app.timezone'),
        'serverNow' => now(),
        'smtp' => $smtp,
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required',
            'body' => 'required',
            'scheduled_at' => 'required|date'
        ]);

        ClientScheduledMail::create([
            'client_id' => session('client.id'),
            'to' => $request->to,
            'subject' => $request->subject,
            'body' => $request->body,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending'
        ]);

        return back()->with('success','Mail programmé avec succès');
    }

    public function destroy($id)
    {
        $mail = ClientScheduledMail::where('client_id', session('client.id'))
            ->where('id',$id)
            ->firstOrFail();

        $mail->delete();

        return back()->with('success','Mail supprimé');
    }
}