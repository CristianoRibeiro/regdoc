<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use stdClass;
use Auth;
use Ramsey\Uuid\Uuid;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_guia;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaRepositoryInterface;

class RegistroFiduciarioPagamentoGuiaRepository implements RegistroFiduciarioPagamentoGuiaRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_pagamento_guia
     * @return registro_fiduciario_pagamento_guia|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_guia) : ?registro_fiduciario_pagamento_guia
    {
        return registro_fiduciario_pagamento_guia::find($id_registro_fiduciario_pagamento_guia);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_pagamento_guia|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario_pagamento_guia
    {
        return registro_fiduciario_pagamento_guia::where('uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_guia
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento_guia
    {
        $novo_pagamento_guia = new registro_fiduciario_pagamento_guia();
        $novo_pagamento_guia->uuid = Uuid::uuid4();
        $novo_pagamento_guia->id_registro_fiduciario_pagamento = $args->id_registro_fiduciario_pagamento;
        $novo_pagamento_guia->id_arquivo_grupo_produto_guia = $args->id_arquivo_grupo_produto_guia ?? NULL;
        $novo_pagamento_guia->id_arquivo_grupo_produto_comprovante = $args->id_arquivo_grupo_produto_comprovante ?? NULL;
        $novo_pagamento_guia->nu_guia = $args->nu_guia ?? NULL;
        $novo_pagamento_guia->nu_serie = $args->nu_serie ?? NULL;
        $novo_pagamento_guia->va_guia = $args->va_guia ?? NULL;
        $novo_pagamento_guia->no_emissor = $args->no_emissor ?? NULL;
        $novo_pagamento_guia->dt_vencimento = $args->dt_vencimento ?? NULL;
        $novo_pagamento_guia->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        $novo_pagamento_guia->id_arisp_boleto = $args->id_arisp_boleto ?? NULL;
        if (!$novo_pagamento_guia->save()) {
            throw new Exception('Erro ao salvar a guia do pagamento.');
        }

        return $novo_pagamento_guia;
    }

    /**
     * @param registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_guia
     * @throws Exception
     */
    public function alterar(registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia, stdClass $args) : registro_fiduciario_pagamento_guia
    {
        if (isset($args->id_arquivo_grupo_produto_guia)) {
            $registro_fiduciario_pagamento_guia->id_arquivo_grupo_produto_guia = $args->id_arquivo_grupo_produto_guia;
        }
        if (isset($args->id_arquivo_grupo_produto_comprovante)) {
            $registro_fiduciario_pagamento_guia->id_arquivo_grupo_produto_comprovante = $args->id_arquivo_grupo_produto_comprovante;
        }
        if (isset($args->nu_guia)) {
            $registro_fiduciario_pagamento_guia->nu_guia = $args->nu_guia;
        }
        if (isset($args->nu_serie)) {
            $registro_fiduciario_pagamento_guia->nu_serie = $args->nu_serie;
        }
        if (isset($args->va_guia)) {
            $registro_fiduciario_pagamento_guia->va_guia = $args->va_guia;
        }
        if (isset($args->dt_vencimento)) {
            $registro_fiduciario_pagamento_guia->dt_vencimento = $args->dt_vencimento;
        }
        if (isset($args->no_emissor)) {
            $registro_fiduciario_pagamento_guia->no_emissor = $args->no_emissor;
        }
        if (isset($args->id_arisp_boleto)) {
            $registro_fiduciario_pagamento_guia->id_arisp_boleto = $args->id_arisp_boleto;
        }

        if (!$registro_fiduciario_pagamento_guia->save()) {
            throw new  Exception('Erro ao atualizar a guia de pagamento.');
        }

        $registro_fiduciario_pagamento_guia->refresh();

        return $registro_fiduciario_pagamento_guia;
    }

    /**
     * @param registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia
     * @return registro_fiduciario_pagamento_guia
     * @throws Exception
     */
    public function remover_comprovante(registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia) : registro_fiduciario_pagamento_guia
    {
        $registro_fiduciario_pagamento_guia->id_arquivo_grupo_produto_comprovante = NULL;

        if (!$registro_fiduciario_pagamento_guia->save()) {
            throw new  Exception('Erro ao atualizar a guia de pagamento.');
        }

        $registro_fiduciario_pagamento_guia->refresh();

        return $registro_fiduciario_pagamento_guia;
    }
}
