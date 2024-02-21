<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Repositories;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\TipoParteRegistroFiduciarioOrdemRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\tipo_parte_registro_fiduciario_ordem;

class TipoParteRegistroFiduciarioOrdemRepository implements TipoParteRegistroFiduciarioOrdemRepositoryInterface
{
   /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args)
    {
        for($i=0;$i<5;$i++) {

            $tipo_parte_registro_fiduciario_ordem = tipo_parte_registro_fiduciario_ordem::select('id_tipo_parte_registro_fiduciario', 'nu_ordem');

            if (is_null($args->id_registro_fiduciario_tipo ?? NULL)) {
                $tipo_parte_registro_fiduciario_ordem = $tipo_parte_registro_fiduciario_ordem->whereNull('id_registro_fiduciario_tipo');
            } else {
                $tipo_parte_registro_fiduciario_ordem = $tipo_parte_registro_fiduciario_ordem->where('id_registro_fiduciario_tipo', $args->id_registro_fiduciario_tipo);
            }
            if (is_null($args->id_registro_fiduciario_assinatura_tipo ?? NULL)) {
                $tipo_parte_registro_fiduciario_ordem = $tipo_parte_registro_fiduciario_ordem->whereNull('id_registro_fiduciario_assinatura_tipo');
            } else {
                $tipo_parte_registro_fiduciario_ordem = $tipo_parte_registro_fiduciario_ordem->where('id_registro_fiduciario_assinatura_tipo', $args->id_registro_fiduciario_assinatura_tipo);
            }
            if (is_null($args->id_pessoa ?? NULL)) {
                $tipo_parte_registro_fiduciario_ordem = $tipo_parte_registro_fiduciario_ordem->whereNull('id_pessoa');
            } else {
                $tipo_parte_registro_fiduciario_ordem = $tipo_parte_registro_fiduciario_ordem->where('id_pessoa', $args->id_pessoa);
            }

            $tipo_parte_registro_fiduciario_ordem = $tipo_parte_registro_fiduciario_ordem->orderBy('nu_ordem', 'ASC')
                ->get();

            if (count($tipo_parte_registro_fiduciario_ordem)>0) {
                return $tipo_parte_registro_fiduciario_ordem;
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
