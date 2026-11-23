<?php

namespace App\Mail;

use App\Models\GiftOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GiftPurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly GiftOrder $order,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Obrigado pelo seu presente! 💚',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.gift-purchased',
        );
    }
}
