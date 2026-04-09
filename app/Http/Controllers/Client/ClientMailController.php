<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientSmtp;
use App\Models\ClientMailLog;
use App\Services\ClientMailService;
use App\Mail\ClientDynamicMail;
use Illuminate\Support\Facades\Mail;

class ClientMailController extends Controller
{
    public function index()
    {
        $clientId = session('client.id');

        $smtp = ClientSmtp::where('client_id',$clientId)->first();
        $logs = ClientMailLog::where('client_id',$clientId)
                    ->latest()
                    ->limit(20)
                    ->get();

        $stats = [
            'total' => ClientMailLog::where('client_id',$clientId)->count(),
            'success' => ClientMailLog::where('client_id',$clientId)->where('status','success')->count(),
            'failed' => ClientMailLog::where('client_id',$clientId)->where('status','failed')->count(),
        ];

        return view('client.mails.envoyes', compact('smtp','logs','stats'));
    }

    public function saveSmtp(Request $request)
    {
        $clientId = session('client.id');

        ClientSmtp::updateOrCreate(
    ['client_id'=>$clientId],
    [
        'host'=>$request->host,
        'port'=>$request->port,
        'encryption'=>$request->encryption,
        'username'=>$request->username,
        'password'=>encrypt($request->password),
        'from_name'=>session('client.first_name'),

        // ✅ RESET STATUS
        'last_test_success' => null,
        'last_tested_at' => null,
    ]
);

        return back()->with('success','SMTP enregistré');
    }

    public function send(Request $request)
    {
        $clientId = session('client.id');

        $smtp = ClientSmtp::where('client_id',$clientId)->first();

        if(!$smtp){
            return back()->with('error','Configurez SMTP');
        }

        try {

            ClientMailService::configure($smtp);

            Mail::mailer('client')
                ->to($request->to)
                ->send(new ClientDynamicMail(
                    $request->subject,
                    $request->message
                ));

            ClientMailLog::create([
                'client_id'=>$clientId,
                'to'=>$request->to,
                'subject'=>$request->subject,
                'body'=>$request->message,
                'status'=>'success'
            ]);

            return back()->with('success','Mail envoyé');

        } catch(\Exception $e){

            ClientMailLog::create([
                'client_id'=>$clientId,
                'to'=>$request->to,
                'subject'=>$request->subject,
                'body'=>$request->message,
                'status'=>'failed',
                'error_message'=>$e->getMessage()
            ]);

            return back()->with('error',$e->getMessage());
        }
    }

    public function testSmtp()
{
    $clientId = session('client.id');

    $smtp = ClientSmtp::where('client_id', $clientId)->first();

    if (!$smtp) {
        return back()->with('error', 'SMTP non configuré');
    }

    try {

        ClientMailService::configure($smtp);

        Mail::mailer('client')
            ->to($smtp->username)
            ->send(new ClientDynamicMail(
                'Test SMTP',
                '<h3>Test SMTP réussi ✅</h3><p>Votre configuration fonctionne.</p>'
            ));

        // ✅ UPDATE SUCCESS
        $smtp->update([
            'last_test_success' => true,
            'last_tested_at' => now(),
        ]);

        return back()->with('success', 'Test SMTP réussi');

    } catch (\Exception $e) {

        // ✅ UPDATE FAILED
        $smtp->update([
            'last_test_success' => false,
            'last_tested_at' => now(),
        ]);

        return back()->with('error', 'Erreur SMTP : ' . $e->getMessage());
    }
}
}