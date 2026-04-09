<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\LicenceController;
use App\Http\Controllers\Client\ClientAuthController;
use App\Http\Controllers\Client\ClientProfileController;
use App\Http\Controllers\Client\ClientUserController;
use App\Http\Controllers\WebScraperController;
use App\Http\Controllers\GoogleScraperController;
use App\Http\Controllers\Client\LeadController;
use App\Http\Controllers\Client\ClientMailController;
use App\Http\Controllers\Client\ClientImapController;
use App\Http\Controllers\Client\ClientScheduledMailController;
use App\Http\Controllers\Client\MailMassController;
use App\Http\Controllers\Client\PromptIaController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\Client\ClientInvoiceController;
use App\Http\Controllers\Client\ClientAiNetworkController;


/*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/

// Page d’accueil = login client
Route::get('/', [ClientAuthController::class, 'showLogin'])
    ->name('client.login');

Route::post('/login', [ClientAuthController::class, 'login'])
    ->name('client.login.submit');

Route::middleware('client')->group(function () {

    Route::get('/home', fn () => view('client.home'))->name('client.home');
    
    //Profil
    Route::get('/profil', [ClientProfileController::class, 'index'])
        ->name('client.profil');

    Route::post('/profil', [ClientProfileController::class, 'update'])
        ->name('client.profil.update');

    Route::post('/profil/password', [ClientProfileController::class, 'updatePassword'])
        ->name('client.profil.password');
        
        
    Route::get('/insee', fn () => view('client.insee'))->name('client.insee');
    Route::get('/chambre-metiers', fn () => view('client.chambre-metiers'))->name('client.chambre');
   
Route::get('/google', [GoogleScraperController::class, 'index'])
    ->name('client.google');

Route::post('/google/scrape', [GoogleScraperController::class, 'scrape'])
    ->name('client.google.scrape');
 
    Route::get('/google/export/pdf', [\App\Http\Controllers\GoogleScraperController::class, 'exportPdf'])
    ->name('client.google.export.pdf');

    Route::delete('/google/delete-selected', [GoogleScraperController::class, 'deleteSelected'])
    ->name('client.google.delete.selected');

    Route::get('/google/export/excel', [GoogleScraperController::class, 'exportExcel'])
    ->name('client.google.export.excel');
Route::post('/google/retry-scraping', [GoogleScraperController::class, 'retryScraping'])
    ->name('client.google.retry-scraping');

Route::get('/google/scraping-stats', [GoogleScraperController::class, 'getScrapingStats'])
    ->name('client.google.scraping-stats');
    Route::get('/web', [WebScraperController::class, 'index'])
    ->name('client.web');

Route::post('/web/scrape', [WebScraperController::class, 'scrape'])
    ->name('client.web.scrape');

    Route::get('/web/export/pdf', [WebScraperController::class, 'exportPdf'])
    ->name('client.web.export.pdf');

    Route::get('/web/export/excel', [WebScraperController::class, 'exportExcel'])
    ->name('client.web.export.excel');

    Route::delete('/web/delete-selected', [WebScraperController::class, 'deleteSelected'])
    ->name('client.web.delete.selected');

    Route::get('/utilisateurs', [ClientUserController::class, 'index'])
            ->name('client.users');

    Route::post('/utilisateurs', [ClientUserController::class, 'store'])
        ->name('client.users.store');

    Route::post('/utilisateurs/{client}', [ClientUserController::class, 'update'])
        ->name('client.users.update');

    Route::delete('/utilisateurs/{client}', [ClientUserController::class, 'destroy'])
        ->name('client.users.delete');
        
    Route::post('/logout', [ClientAuthController::class, 'logout'])
        ->name('client.logout');

    Route::get('/mails/envoyes', [ClientMailController::class,'index'])
    ->name('client.mails.envoyes');

Route::post('/mails/smtp/save', [ClientMailController::class,'saveSmtp'])
    ->name('client.mails.smtp.save');

Route::post('/mails/smtp/test', [ClientMailController::class,'testSmtp'])
    ->name('client.mails.smtp.test');

Route::post('/mails/send', [ClientMailController::class,'send'])
    ->name('client.mails.send');
    Route::get('/mails/recus', [ClientImapController::class,'index'])
    ->name('client.mails.recus');

Route::post('/mails/imap/save', [ClientImapController::class,'save'])
    ->name('client.mails.imap.save');

Route::post('/mails/imap/test', [ClientImapController::class,'test'])
    ->name('client.mails.imap.test');

    Route::post('/mails/imap/sync', [ClientImapController::class,'sync'])
    ->name('client.mails.imap.sync');

    Route::post('/crm/leads/{lead}/generate-mails', 
    [\App\Http\Controllers\Client\LeadController::class, 'generateMails']
)->name('client.crm.leads.generate-mails');

Route::prefix('mails')
    ->name('client.mails.')
    ->group(function () {

        Route::get('/programmes', [ClientScheduledMailController::class,'index'])
            ->name('programmes');

        Route::post('/programmes', [ClientScheduledMailController::class,'store'])
            ->name('programmes.store');

        Route::delete('/programmes/{id}', [ClientScheduledMailController::class,'destroy'])
            ->name('programmes.delete');
});

Route::get('/client/mails/plus', function () {
    return view('client.mails.plus');
})->name('client.mails.plus');

Route::get('/client/mails/plus', [MailMassController::class, 'index'])
    ->name('client.mails.plus');

Route::post('/client/mails/plus', [MailMassController::class, 'send'])
    ->name('client.mails.plus.send');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

// Page login admin
Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::post('/admin/login', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $admin = Admin::where('email', $request->email)->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect('/admin/pages/dashboard');
    }

    return back()->with('error', 'Identifiants incorrects');
});


// Vérification login admin
Route::middleware('admin')->group(function () {
    Route::get('/admin/pages/dashboard', function () {
        return view('admin.pages.dashboard');
    });


    Route::post('/admin/logout', function (Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    });
});

Route::middleware('admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    });

        Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('admin.clients.store');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('admin.clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');


    Route::get('/licences', [LicenceController::class, 'index'])
        ->name('admin.licences.index');

    Route::post('/licences', [LicenceController::class, 'store'])
        ->name('admin.licences.store');

    Route::delete('/licences/{licence}', [LicenceController::class, 'destroy'])
        ->name('admin.licences.destroy');


});

// CRM
Route::get('/crm/leads', [LeadController::class, 'index'])
    ->name('client.crm.leads');

Route::post('/crm/leads', [LeadController::class, 'store'])
    ->name('client.crm.leads.store');

Route::put('/crm/leads/{lead}', [LeadController::class, 'update'])
    ->name('client.crm.leads.update');

Route::delete('/crm/leads/{lead}', [LeadController::class, 'destroy'])
    ->name('client.crm.leads.destroy');

    Route::get('/crm/leads/export', [LeadController::class, 'exportExcel'])
    ->name('client.crm.leads.export');

Route::get('/crm/leads/{lead}/export', [LeadController::class, 'exportSingleExcel'])
    ->name('client.crm.leads.export.single');

    Route::post('/google/export-to-lead/{place}', 
    [GoogleScraperController::class, 'exportToLead'])
    ->name('client.google.export.lead.single');

Route::post('/google/export-to-lead-by-scrapping-with-mails',
    [GoogleScraperController::class, 'exportByScrappingWithMails'])
    ->name('client.google.export.lead.scrapping.with-mails');

Route::post('/google/export-to-lead-by-scrapping-without-mails',
    [GoogleScraperController::class, 'exportByScrappingWithoutMails'])
    ->name('client.google.export.lead.scrapping.without-mails');
// MAILS
Route::get('/mails/envoyes', [ClientMailController::class,'index'])
    ->name('client.mails.envoyes');
    

// routes/web.php
Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])
    ->name('client.crm.dashboard');

    /*
|--------------------------------------------------------------------------
| PROMPT IA
|--------------------------------------------------------------------------
*/

Route::get('/prompt-ia', [PromptIaController::class,'index'])
    ->name('client.prompt-ia');

Route::post('/prompt-ia', [PromptIaController::class,'store'])
->name('client.prompt-ia.store');

Route::put('/prompt-ia/{id}', [PromptIaController::class,'update'])
->name('client.prompt-ia.update');

Route::delete('/prompt-ia/{id}', [PromptIaController::class,'destroy'])
->name('client.prompt-ia.delete');

/*
|--------------------------------------------------------------------------
| COMMUNICATION
|--------------------------------------------------------------------------
*/

Route::get('/communication/whatsapp', function () {
    return view('client.communication.whatsapp');
})->name('client.communication.whatsapp');

Route::get('/communication/sms', function () {
    return view('client.communication.sms');
})->name('client.communication.sms');

Route::post('/send-whatsapp', [WhatsAppController::class, 'send'])->name('send.whatsapp');
Route::post('/send-sms', [SmsController::class, 'send'])->name('send.sms');
Route::post('/crm/leads/{lead}/inline-update', [LeadController::class, 'inlineUpdate'])->name('client.crm.leads.inline-update');

/*
|--------------------------------------------------------------------------
| INVOICE
|--------------------------------------------------------------------------
*/

Route::prefix('invoice/clients')->group(function(){

    Route::get('/',[ClientInvoiceController::class,'index'])->name('clients.index');

    Route::post('/store',[ClientInvoiceController::class,'store'])->name('clients.store');

    Route::post('/update/{id}',[ClientInvoiceController::class,'update'])->name('clients.update');

    Route::delete('/delete/{id}',[ClientInvoiceController::class,'destroy'])->name('clients.delete');

    Route::get('/{id}/edit', [ClientInvoiceController::class, 'edit'])
        ->name('clients.edit');

});
Route::get('/invoice/devis', fn() => view('client.invoice.devis'))
    ->name('client.invoice.devis');

Route::get('/invoice/factures', fn() => view('client.invoice.factures'))
    ->name('client.invoice.factures');

Route::get('/invoice/flux-auto', fn() => view('client.invoice.flux'))
    ->name('client.invoice.flux');

Route::get('/invoice/parametres', fn() => view('client.invoice.params'))
    ->name('client.invoice.params');


/*
|--------------------------------------------------------------------------
| RESEAU IA
|--------------------------------------------------------------------------
*/

Route::get('/client/reseau-ia',[ClientAiNetworkController::class,'index'])
->name('client.reseau.ia');

Route::post('/client/reseau-ia/generate',[ClientAiNetworkController::class,'generate'])
->name('client.reseau.ia.generate');