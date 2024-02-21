<?php

namespace App\Domain\Pessoa\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Pessoa\Models\pessoa;

interface PessoaRepositoryInterface
{
    /**
     * @param array $tipos_pessoa
     * @param array $tipos_serventia
     * @param int $id_cidade
     * @return Collection
     */
    public function pessoa_disponiveis(array $tipos_pessoa, array $tipos_serventia, int $id_cidade) : Collection;

    /**
     * @param stdClass $args
     * @return pessoa
     */
    public function inserir(stdClass $args) : pessoa;

    /**
     * @param pessoa $pessoa
     * @param stdClass $args
     * @return pessoa
     */
    public function alterar(pessoa $pessoa, stdClass $args): pessoa;

    /**
     * @param int $id_pessoa
     * @return pessoa|null
     */
    public function buscar(int $id_pessoa) : ?pessoa;

    /**
     * @return Collection
     */
    public function lista_entidades() : Collection;

    /**
     * @param array $id_tipo_pessoas
     * @return Collection
     */
    public function listar_por_tipo(array $id_tipo_pessoas) : Collection;

    /**
     * @param string $cnpj
     * @return Collection
     */
    public function  buscar_por_cpf_cnpj(string $cpf_ou_cnpj): pessoa;

}
