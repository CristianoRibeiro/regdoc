<?php

namespace App\Domain\Arisp\Contracts;

use stdClass;

use App\Domain\Arisp\Models\arisp_boleto;

interface ArispBoletoRepositoryInterface
{
    /**
     * @param int $id_arisp_boleto
     * @return arisp_boleto|null
     */
    public function buscar(int $id_arisp_boleto) : ?arisp_boleto;

    /**
     * @param string $url_boleto
     * @return arisp_boleto|null
     */
    public function buscar_url(string $url_boleto) : ?arisp_boleto;

    /**
     * @param stdClass $args
     * @return arisp_boleto
     */
    public function inserir(stdClass $args) : arisp_boleto;
}
