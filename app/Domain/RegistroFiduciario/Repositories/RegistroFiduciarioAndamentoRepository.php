<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Carbon\Carbon;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoRepositoryInterface;

use App\Exceptions\RegdocException;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;

use stdClass;

class RegistroFiduciarioAndamentoRepository implements RegistroFiduciarioAndamentoRepositoryInterface
{
    public function inserir_andamento(registro_fiduciario $registro_fiduciario, stdClass $args) : registro_fiduciario_andamento
    {
        $novo_andamento = new registro_fiduciario_andamento();
        $novo_andamento->id_registro_fiduciario_pedido = $registro_fiduciario->registro_fiduciario_pedido->id_registro_fiduciario_pedido;
        $novo_andamento->id_fase_grupo_produto = $args->id_fase_grupo_produto;
        $novo_andamento->id_etapa_fase = $args->id_etapa_fase;
        $novo_andamento->id_usuario_etapa = $args->id_usuario ?? 1;
        $novo_andamento->id_acao_etapa = $args->id_acao_etapa;
        $novo_andamento->in_acao_salva = $args->in_acao_salva;
        $novo_andamento->id_resultado_acao = $args->id_resultado_acao ?? NULL;
        $novo_andamento->in_resultado_salvo = $args->in_resultado_salvo;
        $novo_andamento->id_usuario_cad = $args->id_usuario ?? 1;

        if ($args->in_acao_salva == 'S') {
            $novo_andamento->dt_acao_cad = Carbon::now();
            $novo_andamento->id_usuario_acao = $args->id_usuario ?? 1;
            $novo_andamento->id_pessoa_acao = $args->id_pessoa ?? 1;
        }
        if ($args->in_resultado_salvo == 'S') {
            $novo_andamento->dt_resultado_cad = Carbon::now();
            $novo_andamento->id_usuario_resultado = $args->id_usuario ?? 1;
            $novo_andamento->id_pessoa_resultado = $args->id_pessoa ?? 1;
        }

        if (!$novo_andamento->save()) {
            throw new RegdocException('Erro ao salvar o andamento.');
        }

        return $novo_andamento;
    }
}
