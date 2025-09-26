<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class DepositConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;

    public function __construct(Transaction $deposit)
    {
        $this->deposit = $deposit;
    }

    public function build()
    {
        return $this->subject('Deposit Berhasil Dikonfirmasi')
            ->view('emails.deposit.confirmed')
            ->with([
                'amount' => $this->deposit->amount,
                'reference' => $this->deposit->reference,
                'date' => $this->deposit->created_at->format('d/m/Y H:i'),
                'userName' => $this->deposit->user->name,
            ]);
    }
}
