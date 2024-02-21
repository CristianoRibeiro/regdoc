<?php

namespace App\Domain\Configuracao\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaRepositoryInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

use App\Domain\Configuracao\Models\configuracao_pessoa;

class ConfiguracaoPessoaService implements ConfiguracaoPessoaServiceInterface
{
    /**
     * @var ConfiguracaoPessoaRepositoryInterface
     */
    protected $ConfiguracaoPessoaRepositoryInterface;

    /**
     * ConfiguracaoPessoaService constructor.
     * @param ConfiguracaoPessoaRepositoryInterface $ConfiguracaoPessoaRepositoryInterface
     */
    public function __construct(ConfiguracaoPessoaRepositoryInterface $ConfiguracaoPessoaRepositoryInterface)
    {
        $this->ConfiguracaoPessoaRepositoryInterface = $ConfiguracaoPessoaRepositoryInterface;
    }

    /**
     * @param int $id_pessoa
     * @return Collection
     */
    public function listar(int $id_pessoa) : Collection
    {
        return $this->ConfiguracaoPessoaRepositoryInterface->listar($id_pessoa);
    }

    /**
     * @param int $id_pessoa
     * @param array $slugs
     * @return array
     */
    public function listar_array(int $id_pessoa, array $slugs = []) : array
    {
        return $this->ConfiguracaoPessoaRepositoryInterface->listar_array($id_pessoa, $slugs);
    }
}
