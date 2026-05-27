<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;

class TransactionSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transaction Success',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.success',
            with: [
                'transaction' => $this->transaction,
            ],
        );
    }

    public function attachments(): array
{
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $this->transaction->code;
    $qrCodeImage = base64_encode(file_get_contents($qrCodeUrl));

    $pdf = Pdf::loadView('pdf.boarding-pass', [
        'transaction' => $this->transaction,
        'qrCode' => $qrCodeImage,
    ]);

    return [
        Attachment::fromData(fn() => $pdf->output(), 'boarding-pass.pdf')
            ->withMime('application/pdf'),
    ];
}
}