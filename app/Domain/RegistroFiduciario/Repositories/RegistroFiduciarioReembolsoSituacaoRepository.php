<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use Auth;
use stdClass;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso_situacao;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoSituacaoRepositoryInterface;

class RegistroFiduciarioReembolsoSituacaoRepository implements RegistroFiduciarioReembolsoSituacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_reembolso_situacao::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_reembolso_situacao
     * @return registro_fiduciario_reembolso_situacao|null
     */
    public function buscar(int $id_registro_fiduciario_reembolso_situacao) : ?registro_fiduciario_reembolso_situacao
    {
        return registro_fiduciario_reembolso_situacao::find($id_registro_fiduciario_reembolso_situacao);
    }

     /**
     * @param stdClass $args
     * @return registro_fiduciario_reembolso_situacao
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_reembolso_situacao
    {
        $nova_registro_fiduciario_reembolso_situacao = new registro_fiduciario_reembolso_situacao();
        $nova_registro_fiduciario_reembolso_situacao->no_registro_fiduciario_reembolso_situacao = $args->no_registro_fiduciario_reembolso_situacao;
        if (!$nova_registro_fiduciario_reembolso_situacao->save()) {
            throw new Exception('Erro ao salvar o registro fiduciario reembolso situação.');
        }

        return $nova_registro_fiduciario_reembolso_situacao;
    }

    /**
     * @param registro_fiduciario_reembolso_situacao $registro_fiduciario_reembolso_situacao
     * @param stdClass $args
     * @return registro_fiduciario_reembolso_situacao
     * @throws Exception
     */
    public function alterar(registro_fiduciario_reembolso_situacao $registro_fiduciario_reembolso_situacao, stdClass $args) : registro_fiduciario_reembolso_situacao
    {
        if (isset($args->no_registro_fiduciario_reembolso_situacao)) {
            $registro_fiduciario_reembolso_situacao->no_registro_fiduciario_reembolso_situacao;
        }

        if (!$registro_fiduciario_reembolso_situacao->save()) {
            throw new  Exception('Erro ao atualizar o reembolso situacao.');
        }

        $registro_fiduciario_reembolso_situacao->refresh();

        return $registro_fiduciario_reembolso_situacao;
    }
}
