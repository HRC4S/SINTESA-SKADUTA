<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nama;
    public string $resetUrl;
    public int $expiresInMinutes;

    /**
     * @param  string  $nama              Nama pengguna (siswa/guru) untuk sapaan.
     * @param  string  $resetUrl          Link lengkap ke halaman reset password.
     * @param  int     $expiresInMinutes  Masa berlaku link (menit).
     */
    public function __construct(string $nama, string $resetUrl, int $expiresInMinutes = 60)
    {
        $this->nama = $nama;
        $this->resetUrl = $resetUrl;
        $this->expiresInMinutes = $expiresInMinutes;
    }

    public function build()
    {
        return $this->subject('Reset Password Akun SINTESA')
            ->view('emails.reset-password');
    }
}
