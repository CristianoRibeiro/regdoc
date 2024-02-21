<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

interface RegistroFiduciarioRepositoryInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder;

    /**
     * @param int $id_registro_fiduciario
     * @return registro_fiduciario|null
     */
    public function buscar(int $id_registro_fiduciario): ?registro_fiduciario;

    /**
     * @param string $uuid
     * @return registro_fiduciario|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario;

    /**
     * @param stdClass $args
     * @return registro_fiduciario
     */
    public function inserir(stdClass $args): registro_fiduciario;

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @param stdClass $args
     * @return registro_fiduciario
     * @throws Exception
     */
    public function alterar($registro_fiduciario, stdClass $args) : registro_fiduciario;
}
