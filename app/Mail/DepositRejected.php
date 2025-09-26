<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class DepositRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;

    public function __construct(Transaction $deposit)
    {
        $this->deposit = $deposit;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deposit Ditolak',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit.rejected',
            with: [
                'amount' => $this->deposit->amount,
                'reference' => $this->deposit->reference,
                'date' => $this->deposit->created_at->format('d/m/Y H:i'),
                'userName' => $this->deposit->user->name,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
