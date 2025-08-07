<?php

namespace App\Listeners;

use App\Events\SendEmail;
use App\Mail\InvoiceGeneratedMail;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendEmail $event): void
    {
        Mail::to($event->client_email)->send(new InvoiceGeneratedMail($event->invoice, $event->pdfBinary));
    }
}
