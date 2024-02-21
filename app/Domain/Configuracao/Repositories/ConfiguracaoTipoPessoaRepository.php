<?php

namespace App\Domain\Configuracao\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Configuracao\Contracts\ConfiguracaoTipoPessoaRepositoryInterface;

use App\Domain\Configuracao\Models\configuracao_tipo_pessoa;

class ConfiguracaoTipoPessoaRepository implements ConfiguracaoTipoPessoaRepositoryInterface
{
    /**
     * @param int $id_tipo_pessoa
     * @return Collection
     */
    public function listar(int $id_tipo_pessoa) : Collection
    {
        return configuracao_tipo_pessoa::where('id_tipo_pessoa', $id_tipo_pessoa)->orderBy('dt_cadastro', 'ASC')->get();
    }

}
