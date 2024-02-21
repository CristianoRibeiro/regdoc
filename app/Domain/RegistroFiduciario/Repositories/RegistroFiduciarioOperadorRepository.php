<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Auth;
use stdClass;
use Exception;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperadorRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_operador;

class RegistroFiduciarioOperadorRepository implements RegistroFiduciarioOperadorRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_operador
     * @return registro_fiduciario_operador|null
     */
    public function buscar(int $id_registro_fiduciario_operador): ?registro_fiduciario_operador
    {
        return registro_fiduciario_operador::findOrFail($id_registro_fiduciario_operador);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_operador
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_operador
    {
        $novo_operador = new registro_fiduciario_operador();
        $novo_operador->id_registro_fiduciario = $args->id_registro_fiduciario;
        $novo_operador->id_usuario = $args->id_usuario;
        $novo_operador->id_usuario_cad = Auth::User()->id_usuario;

        if (!$novo_operador->save()) {
            throw new Exception('Erro ao salvar o operador do registro fiduciário.');
        }

        return $novo_operador;
    }

    /**
     * @param registro_fiduciario_operador $registro_fiduciario_operador
     * @param stdClass $args
     * @return registro_fiduciario_operador
     * @throws Exception
     */
    public function alterar(registro_fiduciario_operador $registro_fiduciario_operador, stdClass $args): registro_fiduciario_operador
    {
        $registro_fiduciario_operador->in_registro_ativo = $args->in_registro_ativo;
        $registro_fiduciario_operador->dt_atualizacao = Carbon::now();
        $registro_fiduciario_operador->id_usuario_alt = Auth::User()->id_usuario;

        if (!$registro_fiduciario_operador->save()) 
            throw new Exception('Erro ao atualizar o operador do registro fiduciario.');

        $registro_fiduciario_operador->refresh();
        return $registro_fiduciario_operador;
    }

    /**
     * @param registro_fiduciario_operador $registro_fiduciario_operador
     * @return registro_fiduciario_operador
     * @throws Exception
     */
    public function deletar(registro_fiduciario_operador $registro_fiduciario_operador): registro_fiduciario_operador
    {
        $registro_fiduciario_operador->in_registro_ativo = 'N';
        $registro_fiduciario_operador->dt_exclusao = Carbon::now();
        $registro_fiduciario_operador->id_usuario_del = Auth::User()->id_usuario;

        if (!$registro_fiduciario_operador->save()) 
            throw new Exception('Erro ao deletar o operador do registro fiduciario.');

        $registro_fiduciario_operador->refresh();
        return $registro_fiduciario_operador;
    }
}
