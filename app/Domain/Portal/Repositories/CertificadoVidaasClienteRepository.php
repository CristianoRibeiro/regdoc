<?php

namespace App\Domain\Portal\Repositories;

use stdClass;
use Exception;

use App\Domain\Portal\Contracts\CertificadoVidaasClienteRepositoryInterface;

use App\Domain\Portal\Models\portal_certificado_vidaas_cliente;

class CertificadoVidaasClienteRepository implements CertificadoVidaasClienteRepositoryInterface
{
    /**
     * @param string $no_link
     * @return portal_certificado_vidaas_cliente
     */
    public function buscar_link(string $no_link) : portal_certificado_vidaas_cliente
    {
        return portal_certificado_vidaas_cliente::where('no_link', $no_link)
            ->where('in_registro_ativo', 'S')
            ->firstOrFail();
    }
}
