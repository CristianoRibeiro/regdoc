<?php

namespace App\View\Components\Certificados;

use Illuminate\View\Component;

class DetalhesIdentificacao extends Component
{
    public $parte_emissao_certificado;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($parteemissaocertificado)
    {
        $this->parte_emissao_certificado = $parteemissaocertificado;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.certificados.detalhes-identificacao');
    }
}
