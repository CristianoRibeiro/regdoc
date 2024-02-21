<?php

namespace App\Domain\Usuario\Services;

use stdClass;

use App\Domain\Usuario\Models\usuario_certificado;

use App\Domain\Usuario\Contracts\UsuarioCertificadoRepositoryInterface;
use App\Domain\Usuario\Contracts\UsuarioCertificadoServiceInterface;

class UsuarioCertificadoService implements UsuarioCertificadoServiceInterface
{
    /**
     * @var UsuarioCertificadoRepositoryInterface
     */
    protected $UsuarioCertificadoRepositoryInterface;

    /**
     * UsuarioCertificadoService constructor.
     * @param UsuarioCertificadoRepositoryInterface $UsuarioCertificadoRepositoryInterface
     */
    public function __construct(UsuarioCertificadoRepositoryInterface $UsuarioCertificadoRepositoryInterface)
    {
        $this->UsuarioCertificadoRepositoryInterface = $UsuarioCertificadoRepositoryInterface;
    }

    /**
     * @param int $id_usuario_certificado
     * @return usuario_certificado|null
     */
    public function buscar(int $id_usuario_certificado) : ?usuario_certificado
    {
        return $this->UsuarioCertificadoRepositoryInterface->buscar($id_usuario_certificado);
    }

    /**
     * @param string $nu_serial
     * @return usuario_certificado|null
     */
    public function buscar_serial(string $nu_serial) : ?usuario_certificado
    {
        return $this->UsuarioCertificadoRepositoryInterface->buscar_serial($nu_serial);
    }

    /**
     * @param stdClass $args
     * @return usuario_certificado
     */
    public function inserir(stdClass $args) : usuario_certificado
    {
        return $this->UsuarioCertificadoRepositoryInterface->inserir($args);
    }
}
