<?php

namespace App\Domain\Serventia\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Serventia\Models\serventia;

interface ServentiaServiceInterface
{

    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder;

    /**
     * @param int $id_serventia
     * @return serventia|null
     */
    public function buscar(int $id_serventia) : ?serventia;

    /**
     * @param string $codigo_cns_completo
     * @return serventia|null
     */
    public function buscar_cns(string $codigo_cns_completo) : ?serventia;

    /**
     * @param stdClass $args
     * @return serventia
     */
    public function inserir(stdClass $args): serventia;

    /**
     * @param serventia $serventia
     * @param stdClass $args
     * @return serventia
     */
    public function alterar(serventia $serventia, stdClass $args): serventia;
}
