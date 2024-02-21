<?php

namespace App\Domain\Configuracao\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Configuracao\Contracts\ConfiguracaoRepositoryInterface;

use App\Domain\Configuracao\Models\configuracao;

class ConfiguracaoRepository implements ConfiguracaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return configuracao::orderBy('dt_cadastro', 'ASC')->get();
    }

}
