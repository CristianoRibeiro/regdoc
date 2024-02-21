<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoImovelRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_imovel;

class RegistroFiduciarioVerificacaoImovelRepository implements RegistroFiduciarioVerificacaoImovelRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_verificacoes_imovel
     */
    public function inserir(stdClass $args): registro_fiduciario_verificacoes_imovel
    {
        $registro_verificacao_imovel = new registro_fiduciario_verificacoes_imovel();
        $registro_verificacao_imovel->id_registro_fiduciario = $args->id_registro_fiduciario;
        $registro_verificacao_imovel->no_verificacao = $args->no_verificacao;
        $registro_verificacao_imovel->id_usuario_cad = Auth::User()->id_usuario;
        if (!$registro_verificacao_imovel->save()) {
            throw new Exception('Erro ao salvar a verificação do imovel do registro.');
        }

        return $registro_verificacao_imovel;
    }
}
