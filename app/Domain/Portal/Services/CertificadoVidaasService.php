<?php

namespace App\Domain\Portal\Services;

use stdClass;

use App\Domain\Portal\Contracts\CertificadoVidaasRepositoryInterface;
use App\Domain\Portal\Contracts\CertificadoVidaasServiceInterface;

use App\Domain\Portal\Models\portal_certificado_vidaas;

class CertificadoVidaasService implements CertificadoVidaasServiceInterface
{
    /**
     * @var CertificadoVidaasRepositoryInterface
     */
    protected $CertificadoVidaasRepositoryInterface;

    /**
     * CertificadoVidaasService constructor.
     * @param CertificadoVidaasRepositoryInterface $CertificadoVidaasRepositoryInterface
     */
    public function __construct(CertificadoVidaasRepositoryInterface $CertificadoVidaasRepositoryInterface)
    {
        $this->CertificadoVidaasRepositoryInterface = $CertificadoVidaasRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return portal_certificado_vidaas
     */
    public function inserir(stdClass $args): portal_certificado_vidaas
    {
        return $this->CertificadoVidaasRepositoryInterface->inserir($args);
    }
}
