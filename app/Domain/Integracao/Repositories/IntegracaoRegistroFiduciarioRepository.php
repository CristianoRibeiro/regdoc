<?php

namespace App\Domain\Integracao\Repositories;

use stdClass;

use App\Domain\Integracao\Models\integracao_registro_fiduciario;

use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioRepositoryInterface;

class IntegracaoRegistroFiduciarioRepository implements IntegracaoRegistroFiduciarioRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return int
     */
    public function definir_integracao(stdClass $args) : int
    {
        for($i=0;$i<5;$i++) {
            $integracao_registro_fiduciario = integracao_registro_fiduciario::join('integracao', 'integracao.id_integracao', '=', 'integracao_registro_fiduciario.id_integracao');
            if (is_null($args->id_registro_fiduciario_tipo ?? NULL)) {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->whereNull('id_registro_fiduciario_tipo');
            } else {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->where('id_registro_fiduciario_tipo', $args->id_registro_fiduciario_tipo);
            }
            if (is_null($args->id_grupo_serventia ?? NULL)) {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->whereNull('id_grupo_serventia');
            } else {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->where('id_grupo_serventia', $args->id_grupo_serventia);
            }
            if (is_null($args->id_serventia ?? NULL)) {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->whereNull('id_serventia');
            } else {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->where('id_serventia', $args->id_serventia);
            }
            if (is_null($args->id_pessoa ?? NULL)) {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->whereNull('id_pessoa');
            } else {
                $integracao_registro_fiduciario = $integracao_registro_fiduciario->where('id_pessoa', $args->id_pessoa);
            }

            $integracao_registro_fiduciario = $integracao_registro_fiduciario->first();

            if ($integracao_registro_fiduciario) {
                return $integracao_registro_fiduciario->id_integracao;
            } else {
                end($args);
                $ultima_key = key($args);

                if ($ultima_key) {
                    unset($args->$ultima_key);
                } else {
                    return config('constants.INTEGRACAO.MANUAL');
                }
            }
        }

    }
}
