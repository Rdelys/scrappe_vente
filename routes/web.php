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

Route::post('/google/export-to-lead-by-scrapping', 
    [GoogleScraperController::class, 'exportByScrapping'])
    ->name('client.google.export.lead.scrapping');

// MAILS
Route::get('/mails/programmes', fn () => view('client.mails.programmes'))->name('client.mails.programmes');
Route::get('/mails/envoyes', fn () => view('client.mails.envoyes'))->name('client.mails.envoyes');
Route::get('/mails/recus', fn () => view('client.mails.recus'))->name('client.mails.recus');

// routes/web.php
Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])
    ->name('client.crm.dashboard');
