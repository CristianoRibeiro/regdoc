<?php

namespace App\Domain\Portal\Services;

use stdClass;

use App\Domain\Portal\Contracts\CertificadoVidaasClienteRepositoryInterface;
use App\Domain\Portal\Contracts\CertificadoVidaasClienteServiceInterface;

use App\Domain\Portal\Models\portal_certificado_vidaas_cliente;

class CertificadoVidaasClienteService implements CertificadoVidaasClienteServiceInterface
{
    /**
     * @var CertificadoVidaasClienteRepositoryInterface
     */
    protected $CertificadoVidaasClienteRepositoryInterface;

    /**
     * CertificadoVidaasClienteService constructor.
     * @param CertificadoVidaasClienteRepositoryInterface $CertificadoVidaasClienteRepositoryInterface
     */
    public function __construct(CertificadoVidaasClienteRepositoryInterface $CertificadoVidaasClienteRepositoryInterface)
    {
        $this->CertificadoVidaasClienteRepositoryInterface = $CertificadoVidaasClienteRepositoryInterface;
    }

    /**
     * @param string $no_link
     * @return portal_certificado_vidaas_cliente
     */
    public function buscar_link(string $no_link): portal_certificado_vidaas_cliente
    {
        return $this->CertificadoVidaasClienteRepositoryInterface->buscar_link($no_link);
    }
}
