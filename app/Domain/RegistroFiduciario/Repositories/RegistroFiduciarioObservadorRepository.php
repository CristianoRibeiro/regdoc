<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Auth;
use stdClass;
use Exception;
use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_observador;

class RegistroFiduciarioObservadorRepository implements RegistroFiduciarioObservadorRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_observador
     * @return registro_fiduciario_observador|null
     */
    public function buscar(int $id_registro_fiduciario_observador): ?registro_fiduciario_observador
    {
        return registro_fiduciario_observador::find($id_registro_fiduciario_observador);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_observador
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_observador
    {
        $novo_observador = new registro_fiduciario_observador();
        $novo_observador->uuid = Uuid::uuid4();
        $novo_observador->id_registro_fiduciario = $args->id_registro_fiduciario;
        $novo_observador->no_observador = $args->no_observador;
        $novo_observador->no_email_observador = mb_strtolower($args->no_email_observador, 'UTF-8');
        $novo_observador->id_usuario_cad = Auth::User()->id_usuario;

        if (!$novo_observador->save()) {
            throw new Exception('Erro ao salvar o observador do registro fiduciário.');
        }

        return $novo_observador;
    }

    /**
     * @param registro_fiduciario_observador $registro_fiduciario_observador
     * @param stdClass $args
     * @return registro_fiduciario_observador
     * @throws Exception
     */
    public function alterar(registro_fiduciario_observador $registro_fiduciario_observador, stdClass $args): registro_fiduciario_observador
    {
        $registro_fiduciario_observador->de_observador = $args->de_observador;

        if (!$registro_fiduciario_observador->save())
            throw new Exception('Erro ao atualizar o observador do registro fiduciario.');
        
        $registro_fiduciario_observador->refresh();
        return $registro_fiduciario_observador;
    }

    /**
     * @param registro_fiduciario_observador $registro_fiduciario_observador
     * @return bool
     * @throws Exception
     */
    public function deletar(registro_fiduciario_observador $registro_fiduciario_observador) : bool
    {
        return $registro_fiduciario_observador->delete();
    }
}
