<?php

namespace App\Domain\Usuario\Contracts;

use stdClass;

use App\Domain\Usuario\Models\usuario_certificado;

interface UsuarioCertificadoRepositoryInterface
{
    /**
     * @param int $id_usuario_certificado
     * @return usuario_certificado|null
     */
    public function buscar(int $id_usuario_certificado) : ?usuario_certificado;

    /**
     * @param string $nu_serial
     * @return usuario_certificado|null
     */
    public function buscar_serial(string $nu_serial) : ?usuario_certificado;

    /**
     * @param stdClass $args
     * @return usuario_certificado
     */
    public function inserir(stdClass $args) : usuario_certificado;
}
