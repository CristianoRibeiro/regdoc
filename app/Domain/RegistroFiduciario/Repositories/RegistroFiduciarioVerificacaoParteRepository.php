<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoParteRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_parte;

class RegistroFiduciarioVerificacaoParteRepository implements RegistroFiduciarioVerificacaoParteRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_verificacoes_parte
     */
    public function inserir(stdClass $args): registro_fiduciario_verificacoes_parte
    {
        $registro_verificacao_parte = new registro_fiduciario_verificacoes_parte();
        $registro_verificacao_parte->id_registro_fiduciario_parte = $args->id_registro_fiduciario_parte;
        $registro_verificacao_parte->no_verificacao = $args->no_verificacao;
        $registro_verificacao_parte->id_usuario_cad = Auth::User()->id_usuario;
        if (!$registro_verificacao_parte->save()) {
            throw new Exception('Erro ao salvar a verificação da parte do registro.');
        }

        return $registro_verificacao_parte;
    }
}
