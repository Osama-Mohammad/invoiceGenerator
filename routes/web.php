<?php

use App\Livewire\BusinessCreate;
use App\Livewire\BusinessIndex;
use App\Livewire\InvoiceCreator;
use App\Livewire\InvoiceIndex;
use App\Livewire\InvoiceShow;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use Illuminate\Support\Facades\Mail;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/create-invoice', InvoiceCreator::class)->name('create_invoice');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::prefix('business')->group(function () {
        Route::get('/create', BusinessCreate::class)->name('business_create');
        Route::get('/{user}/index', BusinessIndex::class)->name('business_index');
    });

    Route::prefix('invoice')->group(function () {
        Route::get('/create', InvoiceCreator::class)->name('invoice_create');
        Route::get('/{user}/index', InvoiceIndex::class)->name('invoice_index');
        Route::get('/{invoice}/show', InvoiceShow::class)->name('invoice_show');
    });
});

require __DIR__ . '/auth.php';
