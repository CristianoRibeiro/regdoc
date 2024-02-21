<?php

namespace App\Domain\Pessoa\Services;

use App\Domain\Pessoa\Contracts\PessoaRepositoryInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Pessoa\Models\pessoa;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class PessoaService implements PessoaServiceInterface
{
    /**
     * @var PessoaRepositoryInterface;
     */
    protected $PessoaRepositoryInterface;

    /**
     * PessoaService constructor.
     * @param PessoaRepositoryInterface $PessoaRepositoryInterface
     */
    public function __construct(PessoaRepositoryInterface $PessoaRepositoryInterface)
    {
        $this->PessoaRepositoryInterface = $PessoaRepositoryInterface;
    }

    /**
     * @param array $tipos_pessoa
     * @param array $tipos_serventia
     * @param int $id_cidade
     * @return Collection
     */
    public function pessoa_disponiveis(array $tipos_pessoa = [], array $tipos_serventia = [], int $id_cidade = 0) : Collection
    {
        return $this->PessoaRepositoryInterface->pessoa_disponiveis($tipos_pessoa, $tipos_serventia, $id_cidade);
    }

    /**
     * @param int $id_pessoa
     * @return pessoa|null
     */
    public function buscar(int $id_pessoa) : ?pessoa
    {
        return $this->PessoaRepositoryInterface->buscar($id_pessoa);
    }

    /**
     * @param stdClass $args
     * @return pessoa
     */
    public function inserir(stdClass $args): pessoa
    {
        return $this->PessoaRepositoryInterface->inserir($args);
    }

    /**
     * @param pessoa $pessoa
     * @param stdClass $args
     * @return pessoa
     */
    public function alterar(pessoa $pessoa, stdClass $args): pessoa
    {
        return $this->PessoaRepositoryInterface->alterar($pessoa, $args);
    }

    /**
     * @return Collection
     */
    public function lista_entidades() : Collection
    {
        return $this->PessoaRepositoryInterface->lista_entidades();
    }

    /**
     * @param array $id_tipo_pessoas
     * @return Collection
     */
    public function listar_por_tipo(array $id_tipo_pessoas) : Collection
    {
        return $this->PessoaRepositoryInterface->listar_por_tipo($id_tipo_pessoas);
    }
}
