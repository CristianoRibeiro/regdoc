<?php

namespace App\Domain\Arisp\Contracts;

use stdClass;

use App\Domain\Arisp\Models\arisp_anexo;

interface ArispAnexoRepositoryInterface
{
    /**
     * @param int $id_arisp_anexo
     * @return arisp_anexo|null
     */
    public function buscar(int $id_arisp_anexo) : ?arisp_anexo;

    /**
     * @param string $codigo_anexo
     * @return arisp_anexo|null
     */
    public function buscar_codigo(string $codigo_anexo) : ?arisp_anexo;

    /**
     * @param stdClass $args
     * @return arisp_anexo
     */
    public function inserir(stdClass $args) : arisp_anexo;
}
