<?php

namespace App\Mail;

use App\Models\Rapat;
use App\Models\RapatUndangan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckinVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $rapat;
    public $undangan;
    public $token;

    public function __construct(Rapat $rapat, RapatUndangan $undangan, string $token)
    {
        $this->rapat    = $rapat;
        $this->undangan = $undangan;
        $this->token    = $token;
    }

    public function build()
    {
        $url = route('tamu.rapat.checkin.verify', [
            'rapat' => $this->rapat->id,
            'token' => $this->token,
        ]);

        return $this->subject('Verifikasi Check-in Rapat: '.$this->rapat->judul)
                    ->markdown('emails.checkin-verification')
                    ->with([
                        'rapat'    => $this->rapat,
                        'undangan' => $this->undangan,
                        'url'      => $url,
                    ]);
    }
}
