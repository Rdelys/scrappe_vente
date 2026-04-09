<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ClientSmtp;
use App\Models\ClientMailLog;
use App\Models\Lead;
use App\Services\ClientMailService;
use App\Mail\ClientDynamicMail;

class MailMassController extends Controller
{
    public function index()
    {
        $client = session('client');

        if ($client['role'] === 'superadmin') {

            $leads = Lead::whereHas('client', function ($q) use ($client) {
                $q->where('company', $client['company']);
            })
            ->whereNotNull('email')
            ->get();

        } else {

            $leads = Lead::where('client_id', $client['id'])
                ->whereNotNull('email')
                ->get();
        }

        return view('client.mails.plus', compact('leads'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

        $client = session('client');
        $clientId = $client['id'];

        $smtp = ClientSmtp::where('client_id', $clientId)
            ->where('is_active', 1)
            ->first();

        if (!$smtp) {
            return back()->with('error', 'SMTP non configuré.');
        }

        // 🔥 Emails manuels
        $manualEmails = [];

        if ($request->filled('emails')) {
            $manualEmails = array_map('trim', explode(',', $request->emails));
        }

        // 🔥 Emails depuis leads sélectionnés
        $leadEmails = $request->lead_emails ?? [];

        // 🔥 Fusion + suppression doublons
        $allEmails = array_unique(array_merge($manualEmails, $leadEmails));

        if (empty($allEmails)) {
            return back()->with('error', 'Aucun email sélectionné.');
        }

        try {

            // Configuration SMTP dynamique
            ClientMailService::configure($smtp);

            foreach ($allEmails as $email) {

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                try {

                    Mail::mailer('client')
                        ->to($email)
                        ->send(new ClientDynamicMail(
                            $request->subject,
                            $request->message
                        ));

                    ClientMailLog::create([
                        'client_id' => $clientId,
                        'to'        => $email,
                        'subject'   => $request->subject,
                        'body'      => $request->message,
                        'status'    => 'success'
                    ]);

                } catch (\Exception $mailError) {

                    ClientMailLog::create([
                        'client_id' => $clientId,
                        'to'        => $email,
                        'subject'   => $request->subject,
                        'body'      => $request->message,
                        'status'    => 'failed',
                        'error_message' => $mailError->getMessage()
                    ]);
                }
            }

            return back()->with('success', 'Emails envoyés avec succès.');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}