<?php

namespace App\Domain\Configuracao\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Configuracao\Contracts\ConfiguracaoTipoPessoaRepositoryInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoTipoPessoaServiceInterface;

use App\Domain\Configuracao\Models\configuracao_tipo_pessoa;

class ConfiguracaoTipoPessoaService implements ConfiguracaoTipoPessoaServiceInterface
{
    /**
     * @var ConfiguracaoTipoPessoaRepositoryInterface
     */
    protected $ConfiguracaoTipoPessoaRepositoryInterface;

    /**
     * ConfiguracaoTipoPessoaService constructor.
     * @param ConfiguracaoTipoPessoaRepositoryInterface $ConfiguracaoTipoPessoaRepositoryInterface
     */
    public function __construct(ConfiguracaoTipoPessoaRepositoryInterface $ConfiguracaoTipoPessoaRepositoryInterface)
    {
        $this->ConfiguracaoTipoPessoaRepositoryInterface = $ConfiguracaoTipoPessoaRepositoryInterface;
    }

    /**
     * @param int $id_tipo_pessoa
     * @return Collection
     */
    public function listar(int $id_tipo_pessoa) : Collection
    {
        return $this->ConfiguracaoTipoPessoaRepositoryInterface->listar($id_tipo_pessoa);
    }
}
