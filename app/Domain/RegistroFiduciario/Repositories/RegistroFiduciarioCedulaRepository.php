<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_cedula;
use stdClass;
use Auth;
use Exception;

class RegistroFiduciarioCedulaRepository implements RegistroFiduciarioCedulaRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_cedula
     */
    public function inserir(stdClass $args): registro_fiduciario_cedula
    {
        $registro_cedula = new registro_fiduciario_cedula();
        $registro_cedula->id_registro_fiduciario_cedula_tipo = $args->id_registro_fiduciario_cedula_tipo;
        $registro_cedula->id_registro_fiduciario_cedula_fracao = $args->id_registro_fiduciario_cedula_fracao;
        $registro_cedula->nu_cedula = $args->nu_cedula;
        $registro_cedula->nu_fracao = $args->nu_fracao;
        $registro_cedula->nu_serie = $args->nu_serie;
        $registro_cedula->id_registro_fiduciario_cedula_especie = $args->id_registro_fiduciario_cedula_especie;
        $registro_cedula->de_custo_emissor = $args->de_custo_emissor;
        $registro_cedula->dt_cedula = $args->dt_cedula;
        $registro_cedula->id_usuario_cad = Auth::User()->id_usuario;
        if (!$registro_cedula->save()) {
            throw new Exception('Erro ao salvar a cédula do registro.');
        }

        return $registro_cedula;
    }

    /**
     * @param registro_fiduciario_cedula $registro_fiduciario_cedula
     * @param stdClass $args
     * @return registro_fiduciario_cedula
     * @throws Exception
     */
    public function alterar(registro_fiduciario_cedula $registro_fiduciario_cedula, stdClass $args): registro_fiduciario_cedula
    {
        if (isset($args->id_registro_fiduciario_cedula_tipo)) {
            $registro_fiduciario_cedula->id_registro_fiduciario_cedula_tipo = $args->id_registro_fiduciario_cedula_tipo;
        }
        if (isset($args->id_registro_fiduciario_cedula_fracao)) {
            $registro_fiduciario_cedula->id_registro_fiduciario_cedula_fracao = $args->id_registro_fiduciario_cedula_fracao;
        }
        if (isset($args->nu_cedula)) {
            $registro_fiduciario_cedula->nu_cedula = $args->nu_cedula;
        }
        if (isset($args->nu_fracao)) {
            $registro_fiduciario_cedula->nu_fracao = $args->nu_fracao;
        }
        if (isset($args->nu_serie)) {
            $registro_fiduciario_cedula->nu_serie = $args->nu_serie;
        }
        if (isset($args->id_registro_fiduciario_cedula_especie)) {
            $registro_fiduciario_cedula->id_registro_fiduciario_cedula_especie = $args->id_registro_fiduciario_cedula_especie;
        }
        if (isset($args->de_custo_emissor)) {
            $registro_fiduciario_cedula->de_custo_emissor = $args->de_custo_emissor;
        }
        if (isset($args->dt_cedula)) {
            $registro_fiduciario_cedula->dt_cedula = $args->dt_cedula;
        }

        if (!$registro_fiduciario_cedula->save()) {
            throw new Exception('Erro ao atualizar a cédula do registro.');
        }

        $registro_fiduciario_cedula->refresh();

        return $registro_fiduciario_cedula;
    }
}
