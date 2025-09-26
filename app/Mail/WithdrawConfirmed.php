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

class WithdrawConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Transaction $withdraw
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penarikan Dana Berhasil Diproses - ' . config('app.name'),
            from: config('mail.from.address'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.withdraw.confirmed',
            with: [
                'amount' => $this->withdraw->amount,
                'withdrawalFee' => $this->withdraw->withdrawal_fee ?? 0,
                'totalReceived' => ($this->withdraw->amount ?? 0) - ($this->withdraw->withdrawal_fee ?? 0),
                'reference' => $this->withdraw->reference,
                'date' => $this->withdraw->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i'),
                'approvedDate' => $this->withdraw->approved_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i'),
                'userName' => $this->withdraw->user?->name ?? 'User',
                'paymentMethod' => $this->withdraw->payment_method ?? 'Bank Transfer',
                'userId' => $this->withdraw->user_id,
                'transactionId' => $this->withdraw->id,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('WithdrawConfirmed email failed', [
            'withdraw_id' => $this->withdraw->id,
            'user_id' => $this->withdraw->user_id,
            'error' => $exception->getMessage()
        ]);
    }
}
