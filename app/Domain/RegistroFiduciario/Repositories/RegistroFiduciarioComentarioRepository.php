<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Illuminate\Support\Facades\Auth;
use stdClass;
use Exception;

use Ramsey\Uuid\Uuid;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario_arquivo_grupo;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class RegistroFiduciarioComentarioRepository implements RegistroFiduciarioComentarioRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_comentario
     * @return registro_fiduciario_comentario|null
     */
    public function buscar(int $id_registro_fiduciario_comentario): ?registro_fiduciario_comentario
    {
        return registro_fiduciario_comentario::findOrFail($id_registro_fiduciario_comentario);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_comentario|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_comentario
    {
        return registro_fiduciario_comentario::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_comentario
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_comentario
    {
        $novo_comentario = new registro_fiduciario_comentario();
        $novo_comentario->uuid = Uuid::uuid4();
        $novo_comentario->id_registro_fiduciario = $args->id_registro_fiduciario;
        $novo_comentario->de_comentario = $args->de_comentario;
        $novo_comentario->id_usuario_cad = Auth::User()->id_usuario;
        $novo_comentario->in_direcao = $args->in_direcao;

        if (!$novo_comentario->save()) {
            throw new Exception('Erro ao salvar o comentário do registro fiduciário.');
        }

        return $novo_comentario;
    }

    /**
     * @param registro_fiduciario_comentario $registro_fiduciario_comentario
     * @param stdClass $args
     * @return registro_fiduciario_comentario
     * @throws Exception
     */
    public function alterar(registro_fiduciario_comentario $registro_fiduciario_comentario, stdClass $args): registro_fiduciario_comentario
    {
        $registro_fiduciario_comentario->de_comentario = $args->de_comentario;

        if ($registro_fiduciario_comentario->save()) {
            throw new Exception('Erro ao atualizar o comentário do registro fiduciario.');
        }

        return $registro_fiduciario_comentario;
    }

    /**
     * @param $registro_fiduciario, $filtros
     */
    public function buscar_comentarios_com_filtros(registro_fiduciario $registro_fiduciario, array $filtros)
    {
        $registro_fiduciario_comentarios = $registro_fiduciario->registro_fiduciario_comentarios();
        
        if(isset($filtros['data_inicial'])){
            $registro_fiduciario_comentarios->where('dt_cadastro', '>=', $filtros['data_inicial']);
        }
        
        if(isset($filtros['data_final'])){
            $registro_fiduciario_comentarios->where('dt_cadastro', '<=', $filtros['data_final']);
        }
    
        return $registro_fiduciario_comentarios->get();
    }
}
