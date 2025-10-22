<?php
// app/Mail/ResetPasswordOtp.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;

    public function __construct($otp, $userName = null)
    {
        $this->otp = $otp;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Kode OTP Reset Password')
            ->view('emails.reset-password-otp')
            ->with([
                'otp' => $this->otp,
                'userName' => $this->userName
            ]);
    }
}
