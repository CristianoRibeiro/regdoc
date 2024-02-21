<?php

namespace App\View\Components\Certificados;

use Illuminate\View\Component;

class Situacao extends Component
{
    public $icones_situacoes;
    public $cores_situacoes;
    public $parte_emissao_certificado;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($parteemissaocertificado)
    {
        $this->parte_emissao_certificado = $parteemissaocertificado;

        $this->icones_situacoes = [
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO') => 'fas fa-hourglass-start',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ENVIADO') => 'fas fa-hourglass-start',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGENDADO') => 'fas fa-calendar-check',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.PROBLEMA') => 'fas fa-exclamation-circle',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO') => 'fas fa-check-circle',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.CANCELADO') => 'fas fa-times-circle'
        ];
        $this->cores_situacoes = [
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO') => 'warning',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ENVIADO') => 'warning',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGENDADO') => 'info',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.PROBLEMA') => 'warning',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO') => 'success',
            config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.CANCELADO') => 'danger'
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.certificados.situacao');
    }
}
