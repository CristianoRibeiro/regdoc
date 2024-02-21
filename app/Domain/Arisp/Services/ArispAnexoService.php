<?php

namespace App\Domain\Arisp\Services;

use stdClass;

use App\Domain\Arisp\Models\arisp_anexo;

use App\Domain\Arisp\Contracts\ArispAnexoServiceInterface;
use App\Domain\Arisp\Repositories\ArispAnexoRepository;

class ArispAnexoService implements ArispAnexoServiceInterface
{
    public function __construct(ArispAnexoRepository $ArispAnexoRepository)
    {
        $this->ArispAnexoRepository = $ArispAnexoRepository;
    }

    /**
     * @param int $id_arisp_anexo
     * @return arisp_anexo|null
     */
    public function buscar(int $id_arisp_anexo) : ?arisp_anexo
    {
        return $this->ArispAnexoRepository->buscar($id_arisp_anexo);
    }

    /**
     * @param string $codigo_anexo
     * @return arisp_anexo|null
     */
    public function buscar_codigo(string $codigo_anexo) : ?arisp_anexo
    {
        return $this->ArispAnexoRepository->buscar_codigo($codigo_anexo);
    }

    /**
     * @param stdClass $args
     * @return arisp_anexo
     */
    public function inserir(stdClass $args) : arisp_anexo
    {
        return $this->ArispAnexoRepository->inserir($args);
    }
}
