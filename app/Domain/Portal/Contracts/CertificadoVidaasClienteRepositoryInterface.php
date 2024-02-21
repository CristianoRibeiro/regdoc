<?php

namespace App\Domain\Portal\Contracts;

use stdClass;

use App\Domain\Portal\Models\portal_certificado_vidaas_cliente;

interface CertificadoVidaasClienteRepositoryInterface
{
    /**
     * @param string $no_link
     * @return portal_certificado_vidaas_cliente
     */
    public function buscar_link(string $no_link): portal_certificado_vidaas_cliente;
}
