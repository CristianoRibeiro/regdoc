<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PortalCertificadoVIDaaSMail extends Mailable
{
    use Queueable, SerializesModels;

    public $portal_certificado_vidaas;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($portal_certificado_vidaas)
    {
        $this->portal_certificado_vidaas = $portal_certificado_vidaas;

        $this->onQueue('emails');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('REGDOC - Novo formulário de certificado VIDaaS')
                    ->view('email.portal-certificado.certificado-vidaas');
    }
}
