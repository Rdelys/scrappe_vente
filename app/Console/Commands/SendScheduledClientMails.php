<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClientScheduledMail;
use App\Models\ClientSmtp;
use App\Services\ClientMailService;
use App\Mail\ClientDynamicMail;
use Illuminate\Support\Facades\Mail;

class SendScheduledClientMails extends Command
{
    protected $signature = 'client:send-scheduled-mails';
    protected $description = 'Envoie les mails programmés';

    public function handle()
    {
        $mails = ClientScheduledMail::where('status','pending')
            ->where('scheduled_at','<=', now())
            ->get();

        foreach($mails as $mail){

            $smtp = ClientSmtp::where('client_id',$mail->client_id)
                ->where('is_active',1)
                ->first();

            if(!$smtp) continue;

            try {

                ClientMailService::configure($smtp);

                Mail::mailer('client')
                    ->to($mail->to)
                    ->send(new ClientDynamicMail(
                        $mail->subject,
                        $mail->body
                    ));

                $mail->update([
                    'status'=>'sent',
                    'sent_at'=>now()
                ]);

            } catch(\Exception $e){

                $mail->update([
                    'status'=>'failed',
                    'error_message'=>$e->getMessage()
                ]);
            }
        }

        $this->info('Traitement terminé');
    }
}