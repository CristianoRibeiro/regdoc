<?php

namespace App\Domain\Arisp\Contracts;

use stdClass;

use App\Domain\Arisp\Models\arisp_arquivo;

interface ArispArquivoServiceInterface
{
    /**
     * @param int $id_arisp_arquivo
     * @return arisp_arquivo|null
     */
    public function buscar(int $id_arisp_arquivo) : ?arisp_arquivo;

    /**
     * @param stdClass $args
     * @return arisp_arquivo
     */
    public function inserir(stdClass $args) : arisp_arquivo;
}
