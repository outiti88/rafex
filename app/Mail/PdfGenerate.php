<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PdfGenerate extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    private $motif ;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details , $motif)
    {
            $this->motif = $motif;
            $this->details = $details;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->motif === "pdf"){
            return $this->subject('Facture générée')->view('emails.PdfGenerate');
        }
        if($this->motif === "register"){
            return $this->subject('Bienvenue chez Rafex Delivery')->view('emails.register');
        }
    }
}
