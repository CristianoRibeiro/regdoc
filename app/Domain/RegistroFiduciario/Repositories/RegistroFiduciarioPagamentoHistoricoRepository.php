<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use Auth;
use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_historico;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoHistoricoRepositoryInterface;

class RegistroFiduciarioPagamentoHistoricoRepository implements RegistroFiduciarioPagamentoHistoricoRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_pagamento_historico
     * @return registro_fiduciario_pagamento_historico|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_historico) : ?registro_fiduciario_pagamento_historico
    {
        return registro_fiduciario_pagamento_historico::find($id_registro_fiduciario_pagamento_historico);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_historico
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento_historico
    {
        $novo_pagamento_historico = new registro_fiduciario_pagamento_historico();
        $novo_pagamento_historico->id_registro_fiduciario_pagamento = $args->id_registro_fiduciario_pagamento;
        $novo_pagamento_historico->de_descricao = $args->de_observacao;
        $novo_pagamento_historico->id_usuario_cad = Auth::User()->id_usuario;
        if (!$novo_pagamento_historico->save()) {
            throw new Exception('Erro ao salvar o pagamento historico.');
        }

        return $novo_pagamento_historico;
    }

    /**
     * @param registro_fiduciario_pagamento_historico $registro_fiduciario_pagamento_historico
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_historico
     * @throws Exception
     */
    public function alterar(registro_fiduciario_pagamento_historico $registro_fiduciario_pagamento_historico, stdClass $args) : registro_fiduciario_pagamento_historico
    {
        if (isset($args->de_descricao)) {
            $registro_fiduciario_pagamento_historico->de_descricao = $args->de_descricao;
        }

        if (!$registro_fiduciario_pagamento_historico->save()) {
            throw new  Exception('Erro ao atualizar o historico de pagamento.');
        }

        $registro_fiduciario_pagamento_historico->refresh();

        return $registro_fiduciario_pagamento_historico;
    }
}
