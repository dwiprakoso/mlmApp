<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepositRejected extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Transaction $deposit
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deposit Ditolak - ' . config('app.name'),
            from: config('mail.from.address'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit.rejected',
            with: [
                'amount' => $this->deposit->amount,
                'reference' => $this->deposit->reference,
                'date' => $this->deposit->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i'),
                'userName' => $this->deposit->user?->name ?? 'User',
                'userId' => $this->deposit->user_id,
                'transactionId' => $this->deposit->id,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('DepositRejected email failed', [
            'deposit_id' => $this->deposit->id,
            'user_id' => $this->deposit->user_id,
            'error' => $exception->getMessage()
        ]);
    }
}
