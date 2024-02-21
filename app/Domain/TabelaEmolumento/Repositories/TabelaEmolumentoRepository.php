<?php
namespace App\Domain\TabelaEmolumento\Repositories;

use App\Domain\TabelaEmolumento\Contracts\TabelaEmolumentoRepositoryInterface;

use stdClass;

use App\Domain\TabelaEmolumento\Models\tabela_emolumento;

class TabelaEmolumentoRepository implements TabelaEmolumentoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function calcular_emolumentos(stdClass $args)
    {
        for($i=0;$i<6;$i++) {
            $tabela_emolumento = new tabela_emolumento();
            if (is_null($args->id_produto ?? NULL)) {
                $tabela_emolumento = $tabela_emolumento->whereNull('id_produto');
            } else {
                $tabela_emolumento = $tabela_emolumento->where('id_produto', $args->id_produto);
            }
            if (is_null($args->id_tabela_emolumento_tipo ?? NULL)) {
                $tabela_emolumento = $tabela_emolumento->whereNull('id_tabela_emolumento_tipo');
            } else {
                $tabela_emolumento = $tabela_emolumento->where('id_tabela_emolumento_tipo', $args->id_tabela_emolumento_tipo);
            }
            if (is_null($args->id_estado ?? NULL)) {
                $tabela_emolumento = $tabela_emolumento->whereNull('id_estado');
            } else {
                $tabela_emolumento = $tabela_emolumento->where('id_estado', $args->id_estado);
            }
            if (is_null($args->nu_iss ?? NULL)) {
                $tabela_emolumento = $tabela_emolumento->whereNull('nu_iss');
            } else {
                $tabela_emolumento = $tabela_emolumento->where('nu_iss', $args->nu_iss);
            }
            if (is_null($args->id_cidade ?? NULL)) {
                $tabela_emolumento = $tabela_emolumento->whereNull('id_cidade');
            } else {
                $tabela_emolumento = $tabela_emolumento->where('id_cidade', $args->id_cidade);
            }

            $tabela_emolumento = $tabela_emolumento->orderBy('tabela_emolumento.dt_cadastro', 'ASC')
                ->first();

            if ($tabela_emolumento) {
                $faixa = $tabela_emolumento->tabela_emolumento_faixa()
                    ->where('tabela_emolumento_faixa.vl_ini_faixa', '<=', $args->vl_faixa)
                    ->where(function($where) use ($args) {
                        $where->where('tabela_emolumento_faixa.vl_fim_faixa', '>=', $args->vl_faixa)
                            ->orWhereNull('tabela_emolumento_faixa.vl_fim_faixa');
                    })
                    ->first();

                $valor_emolumentos = $faixa->vl_emolumentos ?? 0;
                if ($faixa->vl_max_emolumentos ?? NULL) {
                    if($valor_emolumentos > $faixa->vl_max_emolumentos) {
                        return $faixa->vl_max_emolumentos;
                    }
                }

                return $valor_emolumentos;
            } else {
                end($args);
                $ultima_key = key($args);

                if ($ultima_key) {
                    unset($args->$ultima_key);
                } else {
                    return [];
                }
            }
        }
    }
}
