<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use  App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

interface RegistroFiduciarioNotaDevolutivaServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva
     * @return registro_fiduciario_nota_devolutiva|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva) : ?registro_fiduciario_nota_devolutiva;

    /**
     * @param string $uuid
     * @return registro_fiduciario_nota_devolutiva|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario_nota_devolutiva;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva
     */
    public function inserir(stdClass $args) : registro_fiduciario_nota_devolutiva;

    /**
     * @param registro_fiduciario_nota_devolutiva $registro_fiduciario_nota_devolutiva
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva
     */
    public function alterar(registro_fiduciario_nota_devolutiva $registro_fiduciario_nota_devolutiva, stdClass $args) : registro_fiduciario_nota_devolutiva;

    public function alterarSituacaoPorFiduciario(registro_fiduciario $registro_fiduciario, int $idSituacao, $id_registro_fiduciario_nota_devolutiva): bool;
}
