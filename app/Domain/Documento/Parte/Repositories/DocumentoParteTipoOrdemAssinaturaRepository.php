<?php

namespace App\Domain\Documento\Parte\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;

use App\Domain\Documento\Parte\Models\documento_parte_tipo_ordem_assinatura;

use App\Domain\Documento\Parte\Contracts\DocumentoParteTipoOrdemAssinaturaRepositoryInterface;

class DocumentoParteTipoOrdemAssinaturaRepository implements DocumentoParteTipoOrdemAssinaturaRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args)
    {
        for($i=0;$i<5;$i++) {

            $documento_parte_tipo_ordem_assinatura = documento_parte_tipo_ordem_assinatura::select('id_documento_parte_tipo', 'nu_ordem_assinatura');
            if (is_null($args->id_documento_tipo ?? NULL)) {
                $documento_parte_tipo_ordem_assinatura = $documento_parte_tipo_ordem_assinatura->whereNull('id_documento_tipo');
            } else {
                $documento_parte_tipo_ordem_assinatura = $documento_parte_tipo_ordem_assinatura->where('id_documento_tipo', $args->id_documento_tipo);
            }
            if (is_null($args->id_documento_assinatura_tipo ?? NULL)) {
                $documento_parte_tipo_ordem_assinatura = $documento_parte_tipo_ordem_assinatura->whereNull('id_documento_assinatura_tipo');
            } else {
                $documento_parte_tipo_ordem_assinatura = $documento_parte_tipo_ordem_assinatura->where('id_documento_assinatura_tipo', $args->id_documento_assinatura_tipo);
            }
            if (is_null($args->id_pessoa ?? NULL)) {
                $documento_parte_tipo_ordem_assinatura = $documento_parte_tipo_ordem_assinatura->whereNull('id_pessoa');
            } else {
                $documento_parte_tipo_ordem_assinatura = $documento_parte_tipo_ordem_assinatura->where('id_pessoa', $args->id_pessoa);
            }

            $documento_parte_tipo_ordem_assinatura = $documento_parte_tipo_ordem_assinatura->orderBy('nu_ordem_assinatura', 'ASC')
                ->get();

            if (count($documento_parte_tipo_ordem_assinatura)>0) {
                return $documento_parte_tipo_ordem_assinatura;
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

    /**
     * @param int $id_documento_parte_tipo_ordem_assinatura
     * @return documento_parte_tipo_ordem_assinatura|null
     */
    public function buscar(int $id_documento_parte_tipo_ordem_assinatura) : ?documento_parte_tipo_ordem_assinatura
    {
        return documento_parte_tipo_ordem_assinatura::find($id_documento_parte_tipo_ordem_assinatura);
    }

    /**
     * @param documento_parte_tipo_ordem_assinatura $documento_parte_tipo_ordem_assinatura
     * @param stdClass $args
     * @return documento_parte_tipo_ordem_assinatura
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_parte_tipo_ordem_assinatura
    {
        $novo_documento_parte_tipo_ordem_assinatura = new documento_parte_tipo_ordem_assinatura();
        $novo_documento_parte_tipo_ordem_assinatura->id_documento_parte_tipo = $args->id_documento_parte_tipo;
        $novo_documento_parte_tipo_ordem_assinatura->id_documento_tipo = $args->id_documento_tipo;
        $novo_documento_parte_tipo_ordem_assinatura->id_documento_assinatura_tipo = $args->id_documento_assinatura_tipo;
        $novo_documento_parte_tipo_ordem_assinatura->id_pessoa = $args->id_pessoa;
        $novo_documento_parte_tipo_ordem_assinatura->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        if (!$novo_documento_parte_tipo_ordem_assinatura->save()) {
            throw new Exception('Erro ao salvar a ordem de assinatura do tipo de parte.');
        }

        return $novo_pagamento;
    }
}
