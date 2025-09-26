<?php

// app/Mail/DepositNotification.php
namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function build()
    {
        return $this->subject('New Deposit Request - ' . $this->transaction->reference)
            ->view('emails.deposit-notification')
            ->with([
                'transaction' => $this->transaction,
                'user' => $this->transaction->user
            ]);
    }
}
