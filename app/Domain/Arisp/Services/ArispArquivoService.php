<?php

namespace App\Domain\Arisp\Services;

use stdClass;

use App\Domain\Arisp\Models\arisp_arquivo;

use App\Domain\Arisp\Contracts\ArispArquivoServiceInterface;
use App\Domain\Arisp\Repositories\ArispArquivoRepository;

class ArispArquivoService implements ArispArquivoServiceInterface
{
    public function __construct(ArispArquivoRepository $ArispArquivoRepository)
    {
        $this->ArispArquivoRepository = $ArispArquivoRepository;
    }

    /**
     * @param int $id_arisp_arquivo
     * @return arisp_arquivo|null
     */
    public function buscar(int $id_arisp_arquivo) : ?arisp_arquivo
    {
        return $this->ArispArquivoRepository->buscar($id_arisp_arquivo);
    }

    /**
     * @param stdClass $args
     * @return arisp_arquivo
     */
    public function inserir(stdClass $args) : arisp_arquivo
    {
        return $this->ArispArquivoRepository->inserir($args);
    }
}
