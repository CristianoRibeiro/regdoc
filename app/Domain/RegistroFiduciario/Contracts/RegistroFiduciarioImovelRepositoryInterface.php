<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel;

interface RegistroFiduciarioImovelRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_imovel
     * @param bool $retornar_endereco
     * @return registro_fiduciario_imovel|null
     */
    public function buscar(int $id_registro_fiduciario_imovel, bool $retornar_endereco = false): ?registro_fiduciario_imovel;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_imovel
     */
    public function inserir(stdClass $args): registro_fiduciario_imovel;

    /**
     * @param registro_fiduciario_imovel $registro_fiduciario_imovel
     * @param stdClass $args
     * @return registro_fiduciario_imovel
     */
    public function alterar(registro_fiduciario_imovel $registro_fiduciario_imovel, stdClass $args) : registro_fiduciario_imovel;

    /**
     * @param registro_fiduciario_imovel $registro_fiduciario_imovel
     * @return bool
     */
    public function deletar(registro_fiduciario_imovel $registro_fiduciario_imovel) : bool;
}
