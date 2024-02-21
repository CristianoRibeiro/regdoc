<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioEnderecoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_endereco;
use stdClass;
use Auth;
use Exception;

class RegistroFiduciarioEnderecoRepository implements RegistroFiduciarioEnderecoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_endereco
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_endereco
    {
        $registro_endereco = new registro_fiduciario_endereco();
        $registro_endereco->id_registro_fiduciario = $args->id_registro_fiduciario;
        $registro_endereco->nu_cep = $args->nu_cep ?? NULL;
        $registro_endereco->no_endereco = $args->no_endereco;
        $registro_endereco->nu_endereco = $args->nu_endereco;
        $registro_endereco->no_bairro = $args->no_bairro;
        $registro_endereco->no_complemento = $args->no_complemento;
        $registro_endereco->id_cidade = $args->id_cidade;
        $registro_endereco->id_usuario_cad = Auth::User()->id_usuario;
        if (!$registro_endereco->save()) {
            throw new Exception('Erro ao salvar o endereço do registro.');
        }

        return $registro_endereco;
    }

    /**
     * @param registro_fiduciario_endereco $registro_fiduciario_endereco
     * @param stdClass $args
     * @return registro_fiduciario_endereco
     * @throws Exception
     */
    public function alterar(registro_fiduciario_endereco $registro_fiduciario_endereco, stdClass $args): registro_fiduciario_endereco
    {
        if (isset($args->nu_cep)) {
            $registro_fiduciario_endereco->nu_cep = $args->nu_cep;
        }
        if (isset($args->no_endereco)) {
            $registro_fiduciario_endereco->no_endereco = $args->no_endereco;
        }
        if (isset($args->nu_endereco)) {
            $registro_fiduciario_endereco->nu_endereco = $args->nu_endereco;
        }
        if (isset($args->no_bairro)) {
            $registro_fiduciario_endereco->no_bairro = $args->no_bairro;
        }
        if (isset($args->no_complemento)) {
            $registro_fiduciario_endereco->no_complemento = $args->no_complemento;
        }
        if (isset($args->id_cidade)) {
            $registro_fiduciario_endereco->id_cidade = $args->id_cidade;
        }

        if (!$registro_fiduciario_endereco->save()) {
            throw new Exception('Erro ao atualizar o endereço do registro.');
        }

        $registro_fiduciario_endereco->refresh();

        return $registro_fiduciario_endereco;
    }

    /**
     * @param registro_fiduciario_endereco $registro_fiduciario_endereco
     * @return bool
     * @throws Exception
     */
    public function deletar(registro_fiduciario_endereco $registro_fiduciario_endereco) : bool
    {
        return $registro_fiduciario_endereco->delete();
    }
}
