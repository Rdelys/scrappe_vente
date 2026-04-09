<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Commands
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/*
|--------------------------------------------------------------------------
| Scheduled Tasks (Laravel 12)
|--------------------------------------------------------------------------
*/

// 1️⃣ Expirer automatiquement les licences
Schedule::command('licences:expire')
    ->daily()
    ->description('Expire automatiquement les licences dépassées');

// 2️⃣ Notifier les clients avant expiration (7 jours)
Schedule::command('licences:notify-expiring 7')
    ->daily()
    ->description('Notifier les clients avant expiration de licence');

    Schedule::command('client:send-scheduled-mails')
->everyMinute();