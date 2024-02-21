<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Repositories;

use stdClass;
use Auth;
use Exception;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaRepositoryInterface;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura;

class RegistroFiduciarioAssinaturaRepository implements RegistroFiduciarioAssinaturaRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_assinatura
     * @return registro_fiduciario_assinatura|null
     */
    public function buscar(int $id_registro_fiduciario_assinatura): ?registro_fiduciario_assinatura
    {
        return registro_fiduciario_assinatura::findOrFail($id_registro_fiduciario_assinatura);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_assinatura|null
     */
    public function buscar_pdavh_uuid(string $uuid): ?registro_fiduciario_assinatura
    {
        return registro_fiduciario_assinatura::where('co_process_uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_assinatura
    {
        $registro_fiduciario_assinatura = new registro_fiduciario_assinatura();
        $registro_fiduciario_assinatura->id_registro_fiduciario_assinatura_tipo = $args->id_registro_fiduciario_assinatura_tipo;
        $registro_fiduciario_assinatura->id_registro_fiduciario = $args->id_registro_fiduciario;
        $registro_fiduciario_assinatura->co_process_uuid = $args->co_process_uuid ?? NULL;
        $registro_fiduciario_assinatura->in_ordem_assinatura = $args->in_ordem_assinatura ?? 'N';
        $registro_fiduciario_assinatura->nu_ordem_assinatura_atual = $args->nu_ordem_assinatura_atual ?? 0;
        $registro_fiduciario_assinatura->id_usuario_cad = Auth::id();

        if (!$registro_fiduciario_assinatura->save()) {
            throw new Exception('Erro ao salvar a assinatura do registro.');
        }

        return $registro_fiduciario_assinatura;
    }

    /**
     * @param registro_fiduciario_assinatura $registro_fiduciario_assinatura
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     * @throws Exception
     */
    public function alterar(registro_fiduciario_assinatura $registro_fiduciario_assinatura, stdClass $args): registro_fiduciario_assinatura
    {
        if (isset($args->co_process_uuid)) {
            $registro_fiduciario_assinatura->co_process_uuid = $args->co_process_uuid;
        }
        if (isset($args->in_ordem_assinatura)) {
            $registro_fiduciario_assinatura->in_ordem_assinatura = $args->in_ordem_assinatura;
        }
        if (isset($args->nu_ordem_assinatura_atual)) {
            $registro_fiduciario_assinatura->nu_ordem_assinatura_atual = $args->nu_ordem_assinatura_atual;
        }

        if (!$registro_fiduciario_assinatura->save()) {
            throw new Exception('Erro ao atualizar a assinatura do registro.');
        }

        $registro_fiduciario_assinatura->refresh();

        return $registro_fiduciario_assinatura;
    }
}
