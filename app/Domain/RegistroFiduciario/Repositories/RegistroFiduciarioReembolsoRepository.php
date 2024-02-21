<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use Auth;
use stdClass;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoRepositoryInterface;

class RegistroFiduciarioReembolsoRepository implements RegistroFiduciarioReembolsoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_reembolso::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_reembolso
     * @return registro_fiduciario_reembolso|null
     */
    public function buscar(int $id_registro_fiduciario_reembolso) : ?registro_fiduciario_reembolso
    {
        return registro_fiduciario_reembolso::find($id_registro_fiduciario_reembolso);
    }

     /**
     * @param stdClass $args
     * @return registro_fiduciario_reembolso
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_reembolso
    {
        $nova_registro_fiduciario_reembolso = new registro_fiduciario_reembolso();
        $nova_registro_fiduciario_reembolso->id_registro_fiduciario = $args->id_registro_fiduciario;
        $nova_registro_fiduciario_reembolso->id_registro_fiduciario_reembolso_situacao = $args->id_registro_fiduciario_reembolso_situacao;
        $nova_registro_fiduciario_reembolso->de_observacoes = $args->de_observacoes;
        $nova_registro_fiduciario_reembolso->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        if (!$nova_registro_fiduciario_reembolso->save()) {
            throw new Exception('Erro ao salvar o reembolso.');
        }

        return $nova_registro_fiduciario_reembolso;
    }

    /**
     * @param registro_fiduciario_reembolso $registro_fiduciario_reembolso
     * @param stdClass $args
     * @return registro_fiduciario_reembolso
     * @throws Exception
     */
    public function alterar(registro_fiduciario_reembolso $registro_fiduciario_reembolso, stdClass $args) : registro_fiduciario_reembolso
    {
        if (isset($args->id_registro_fiduciario)) {
            $registro_fiduciario_reembolso->id_registro_fiduciario;
        }

        if (isset($args->id_registro_fiduciario_reembolso_situacao)) {
            $registro_fiduciario_reembolso->id_registro_fiduciario_reembolso_situacao;
        }

        if (isset($args->de_observacoes)) {
            $registro_fiduciario_reembolso->de_observacoes;
        }

        if (!$registro_fiduciario_reembolso->save()) {
            throw new  Exception('Erro ao atualizar o reembolso.');
        }

        $registro_fiduciario_reembolso->refresh();

        return $registro_fiduciario_reembolso;
    }
}
