<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte;

interface RegistroFiduciarioParteServiceInterface
{
    /**
     * @param int|null $id_tipo_parte_registro_fiduciario ;
     * @param int|null $id_pessoa ;
     * @return Collection
     */
    public function listar_agrupado(?int $id_tipo_parte_registro_fiduciario, ?int $id_pessoa): Collection;

    /**
     * @param int $id_registro_fiduciario_parte
     * @return registro_fiduciario_parte|null
     */
    public function buscar(int $id_registro_fiduciario_parte): ?registro_fiduciario_parte;

    /**
     * @param array $ids_registro_fiduciario_parte
     * @return registro_fiduciario_parte|null
     */
    public function buscar_ids(array $ids_registro_fiduciario_parte): Collection;

    /**
     * @param string $uuid
     * @return registro_fiduciario_parte|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_parte;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte
     */
    public function inserir(stdClass $args): registro_fiduciario_parte;

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @param stdClass $args
     * @return registro_fiduciario_parte
     */
    public function alterar(registro_fiduciario_parte $registro_fiduciario_parte, stdClass $args): registro_fiduciario_parte;

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @return bool
     */
    public function deletar(registro_fiduciario_parte $registro_fiduciario_parte) : bool;

    /**
     * @param stdClass $args
     * @return bool
     */
    public function verificar_cpf(stdClass $args) : bool;

    public function definir_qualificacao(registro_fiduciario_parte $registro_fiduciario_parte, stdClass $args): string;

    /**
     * @return Collection<registro_fiduciario_parte>
     */
    public function buscar_por_cpf_cnpj(string $nu_cpf_cnpj): Collection;
}
