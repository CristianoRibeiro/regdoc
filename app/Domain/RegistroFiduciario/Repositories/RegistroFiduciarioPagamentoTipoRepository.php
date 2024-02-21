<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoTipoRepositoryInterface;

use Exception;
use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_tipo;

class RegistroFiduciarioPagamentoTipoRepository implements RegistroFiduciarioPagamentoTipoRepositoryInterface
{

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_pagamento_tipo::where('in_registro_ativo', 'S')->orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_pagamento_tipo
     * @return registro_fiduciario_pagamento_tipo|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_tipo) : ?registro_fiduciario_pagamento_tipo
    {
        return registro_fiduciario_pagamento_tipo::find($id_registro_fiduciario_pagamento_tipo);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_tipo
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento_tipo
    {
        $novo_pagamento_tipo = new registro_fiduciario_pagamento_tipo();
        $novo_pagamento_tipo->no_registro_fiduciario_pagamento_tipo = $args->no_registro_fiduciario_pagamento_tipo;
        $novo_pagamento_tipo->nu_limite_pagamentos = $args->nu_limite_pagamentos;
        $novo_pagamento_tipo->nu_limite_guias = $args->nu_limite_guias;

        if (!$novo_pagamento_tipo->save()) {
            throw new Exception('Erro ao salvar o tipo pagamento.');
        }

        return $novo_pagamento_tipo;
    }

    /**
     * @param registro_fiduciario_pagamento_tipo $registro_fiduciario_pagamento_tipo
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_tipo
     * @throws Exception
     */
    public function alterar(registro_fiduciario_pagamento_tipo $registro_fiduciario_pagamento_tipo, stdClass $args) : registro_fiduciario_pagamento_tipo
    {
        if (isset($args->no_registro_fiduciario_pagamento_tipo)) {
            $registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo = $args->no_registro_fiduciario_pagamento_tipo;
        }
        if (isset($args->nu_limite_pagamentos)) {
            $registro_fiduciario_pagamento_tipo->nu_limite_pagamentos = $args->nu_limite_pagamentos;
        }
        if (isset($args->nu_limite_guias)) {
            $registro_fiduciario_pagamento_tipo->nu_limite_guias = $args->nu_limite_guias;
        }

        if (!$registro_fiduciario_pagamento_tipo->save()) {
            throw new  Exception('Erro ao atualizar o tipo de pagamento.');
        }

        $registro_fiduciario_pagamento_tipo->refresh();

        return $registro_fiduciario_pagamento_tipo;
    }
}
