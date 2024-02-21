<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use Auth;
use stdClass;
use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class RegistroFiduciarioPagamentoRepository implements RegistroFiduciarioPagamentoRepositoryInterface
{
        /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_pagamento::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_pagamento
     * @return registro_fiduciario_pagamento|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento) : ?registro_fiduciario_pagamento
    {
        return registro_fiduciario_pagamento::find($id_registro_fiduciario_pagamento);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_pagamento|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario_pagamento
    {
        return registro_fiduciario_pagamento::where('uuid', $uuid)->first();
    }

    /**
     * @param registro_fiduciario_pagamento $registro_fiduciario_pagamento
     * @param stdClass $args
     * @return registro_fiduciario_pagamento
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento
    {
        $novo_pagamento = new registro_fiduciario_pagamento();
        $novo_pagamento->uuid = Uuid::uuid4();
        $novo_pagamento->id_registro_fiduciario = $args->id_registro_fiduciario;
        $novo_pagamento->id_registro_fiduciario_pagamento_situacao = $args->id_registro_fiduciario_pagamento_situacao;
        $novo_pagamento->id_registro_fiduciario_pagamento_tipo = $args->id_registro_fiduciario_pagamento_tipo;
        $novo_pagamento->de_observacao = $args->de_observacao;
        $novo_pagamento->in_isento = $args->in_isento;
        $novo_pagamento->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        $novo_pagamento->id_arquivo_grupo_produto_isencao = $args->id_arquivo_grupo_produto_isencao ?? NULL;
        if (!$novo_pagamento->save()) {
            throw new Exception('Erro ao salvar o pagamento.');
        }

        return $novo_pagamento;
    }

    /**
     * @param registro_fiduciario_pagamento $registro_fiduciario_pagamento
     * @param stdClass $args
     * @return registro_fiduciario_pagamento
     * @throws Exception
     */
    public function alterar(registro_fiduciario_pagamento $registro_fiduciario_pagamento, stdClass $args) : registro_fiduciario_pagamento
    {
        if (isset($args->id_registro_fiduciario_pagamento_situacao)) {
            $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento_situacao = $args->id_registro_fiduciario_pagamento_situacao;
        }
        if (isset($args->id_registro_fiduciario_pagamento_tipo)) {
            $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento_tipo = $args->id_registro_fiduciario_pagamento_tipo;
        }
        if (isset($args->de_observacao)) {
            $registro_fiduciario_pagamento->de_observacao = $args->de_observacao;
        }
        if (isset($args->in_isento)) {
            $registro_fiduciario_pagamento->in_isento = $args->in_isento;
        }
        if (isset($args->id_arquivo_grupo_produto_isencao)) {
            $registro_fiduciario_pagamento->id_arquivo_grupo_produto_isencao = $args->id_arquivo_grupo_produto_isencao;
        }

        if (!$registro_fiduciario_pagamento->save()) {
            throw new  Exception('Erro ao atualizar o pagamento.');
        }

        $registro_fiduciario_pagamento->refresh();

        return $registro_fiduciario_pagamento;
    }
}
