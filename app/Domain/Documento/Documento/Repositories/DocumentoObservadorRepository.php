<?php

namespace App\Domain\Documento\Documento\Repositories;

use Auth;
use stdClass;
use Exception;
use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Documento\Contracts\DocumentoObservadorRepositoryInterface;
use App\Domain\Documento\Documento\Models\documento_observador;

class DocumentoObservadorRepository implements DocumentoObservadorRepositoryInterface
{
    /**
     * @param int $id_documento_observador
     * @return documento_observador|null
     */
    public function buscar(int $id_documento_observador): ?documento_observador
    {
        return documento_observador::find($id_documento_observador);
    }

    /**
     * @param stdClass $args
     * @return documento_observador
     * @throws Exception
     */
    public function inserir(stdClass $args): documento_observador
    {
        $novo_observador = new documento_observador();
        $novo_observador->uuid = Uuid::uuid4();
        $novo_observador->id_documento = $args->id_documento;
        $novo_observador->no_observador = $args->no_observador;
        $novo_observador->no_email_observador = mb_strtolower($args->no_email_observador, 'UTF-8');
        $novo_observador->id_usuario_cad = Auth::User()->id_usuario;

        if (!$novo_observador->save()) {
            throw new Exception('Erro ao salvar o observador do registro fiduciário.');
        }

        return $novo_observador;
    }

    /**
     * @param documento_observador $documento_observador
     * @param stdClass $args
     * @return documento_observador
     * @throws Exception
     */
    public function alterar(documento_observador $documento_observador, stdClass $args): documento_observador
    {
        $documento_observador->de_observador = $args->de_observador;

        if ($documento_observador->save()) {
            throw new Exception('Erro ao atualizar o observador do registro fiduciario.');
        }
    }

    /**
     * @param documento_observador $documento_observador
     * @return bool
     * @throws Exception
     */
    public function deletar(documento_observador $documento_observador) : bool
    {
        return $documento_observador->delete();
    }
}
