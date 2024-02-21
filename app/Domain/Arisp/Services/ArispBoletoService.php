<?php

namespace App\Domain\Arisp\Services;

use stdClass;

use App\Domain\Arisp\Models\arisp_boleto;

use App\Domain\Arisp\Contracts\ArispBoletoServiceInterface;
use App\Domain\Arisp\Repositories\ArispBoletoRepository;

class ArispBoletoService implements ArispBoletoServiceInterface
{
    public function __construct(ArispBoletoRepository $ArispBoletoRepository)
    {
        $this->ArispBoletoRepository = $ArispBoletoRepository;
    }

    /**
     * @param int $id_arisp_boleto
     * @return arisp_boleto|null
     */
    public function buscar(int $id_arisp_boleto) : ?arisp_boleto
    {
        return $this->ArispBoletoRepository->buscar($id_arisp_boleto);
    }

    /**
     * @param string $url_boleto
     * @return arisp_boleto|null
     */
    public function buscar_url(string $url_boleto) : ?arisp_boleto
    {
        return $this->ArispBoletoRepository->buscar_url($url_boleto);
    }

    /**
     * @param stdClass $args
     * @return arisp_boleto
     */
    public function inserir(stdClass $args) : arisp_boleto
    {
        return $this->ArispBoletoRepository->inserir($args);
    }
}
