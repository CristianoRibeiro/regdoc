<?php

namespace App\Domain\Portal\Contracts;

use stdClass;

use App\Domain\Portal\Models\portal_certificado_vidaas;

interface CertificadoVidaasRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return portal_certificado_vidaas
     */
    public function inserir(stdClass $args) : portal_certificado_vidaas;
}
