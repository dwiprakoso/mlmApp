<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $withdrawalFee;
    public $netAmount;

    public function __construct(Transaction $transaction, $withdrawalFee, $netAmount)
    {
        $this->transaction = $transaction;
        $this->withdrawalFee = $withdrawalFee;
        $this->netAmount = $netAmount;
    }

    public function build()
    {
        return $this->subject('New Withdrawal Request - ' . $this->transaction->reference)
            ->view('emails.withdrawal-notification')
            ->with([
                'transaction' => $this->transaction,
                'user' => $this->transaction->user,
                'wallet' => $this->transaction->wallet,
                'withdrawalFee' => $this->withdrawalFee,
                'netAmount' => $this->netAmount
            ]);
    }
}
