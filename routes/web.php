<?php

use App\Livewire\InvoiceCreator;
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
});

require __DIR__ . '/auth.php';


// Route::get('/test-mail', function () {
//     Mail::raw('This is a test email sent via Mailtrap', function ($message) {
//         $message->to('test@example.com')
//             ->subject('Mailtrap Test');
//     });

//     return 'Email sent!';
// });
