<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_credor;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

interface RegistroFiduciarioCredorServiceInterface
{
    /**
     * @param int $id_registro_fiduciario_credor
     * @return registro_fiduciario_credor|null
     */
    public function buscar(int $id_registro_fiduciario_credor) : ?registro_fiduciario_credor;

    /**
     * @param string $nu_cpf_cnpj
     * @return registro_fiduciario_credor|null
     */
    public function buscar_cnpj(string $nu_cpf_cnpj) : ?registro_fiduciario_credor;

    /**
     * @param int $id_cidade
     * @return Collection
     */
    public function credores_disponiveis(int $id_cidade) : Collection;

    /**
     * @param int $id_cidade
     * @param int $id_pessoa
     * @return Collection
     */
    public function credores_disponiveis_agencia(int $id_cidade, int $id_pessoa) : Collection;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_credor
     */
    public function insere(stdClass $args) : registro_fiduciario_credor;
}
