<?php

namespace App\Domain\Configuracao\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaRepositoryInterface;

use App\Domain\Configuracao\Models\configuracao_pessoa;

class ConfiguracaoPessoaRepository implements ConfiguracaoPessoaRepositoryInterface
{
    /**
     * @param int $id_pessoa
     * @return Collection
     */
    public function listar(int $id_pessoa) : Collection
    {
        return configuracao_pessoa::where('id_pessoa', $id_pessoa)->orderBy('dt_cadastro', 'ASC')->get();
    }

    /**
     * @param int $id_pessoa
     * @param array $slugs
     * @return array
     */
    public function listar_array(int $id_pessoa, array $slugs) : array
    {

        $configuracao_pessoa = new configuracao_pessoa();
        $configuracao_pessoa = $configuracao_pessoa->select('configuracao.no_slug', 'configuracao_pessoa.no_valor')
            ->join('configuracao', 'configuracao.id_configuracao', '=', 'configuracao_pessoa.id_configuracao')
            ->where('id_pessoa', $id_pessoa);

        if(count($slugs)>0) {
            $configuracao_pessoa = $configuracao_pessoa->whereIn('configuracao.no_slug', $slugs);
        }

        $configuracao_pessoa = $configuracao_pessoa->get()
            ->keyBy('no_slug')
            ->transform(function ($configuracao) {
                return $configuracao->no_valor;
            })
            ->toArray();

        return $configuracao_pessoa;

    }

}
