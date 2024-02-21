<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Repositories;

use stdClass;
use Auth;
use Exception;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura;

class RegistroFiduciarioParteAssinaturaRepository implements RegistroFiduciarioParteAssinaturaRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_parte_assinatura
     * @return registro_fiduciario_parte_assinatura|null
     */
    public function buscar(int $id_registro_fiduciario_parte_assinatura): ?registro_fiduciario_parte_assinatura
    {
        return registro_fiduciario_parte_assinatura::find($id_registro_fiduciario_parte_assinatura);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_parte_assinatura
    {
        $registro_fiduciario_parte_assinatura = new registro_fiduciario_parte_assinatura();
        $registro_fiduciario_parte_assinatura->id_registro_fiduciario_assinatura = $args->id_registro_fiduciario_assinatura;
        $registro_fiduciario_parte_assinatura->id_registro_fiduciario_parte = $args->id_registro_fiduciario_parte;
        $registro_fiduciario_parte_assinatura->id_registro_fiduciario_procurador = $args->id_registro_fiduciario_procurador ?? NULL;
        $registro_fiduciario_parte_assinatura->nu_ordem_assinatura = $args->nu_ordem_assinatura ?? NULL;
        $registro_fiduciario_parte_assinatura->id_usuario_cad = Auth::id();

        if (!$registro_fiduciario_parte_assinatura->save()) {
            throw new Exception('Erro ao salvar a assinatura da parte.');
        }

        return $registro_fiduciario_parte_assinatura;
    }

    /**
     * @param registro_fiduciario_parte_assinatura $registro_fiduciario_parte_assinatura
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura
     * @throws Exception
     */
    public function alterar(registro_fiduciario_parte_assinatura $registro_fiduciario_parte_assinatura, stdClass $args): registro_fiduciario_parte_assinatura
    {
        if (isset($args->no_process_url)) {
            $registro_fiduciario_parte_assinatura->no_process_url = $args->no_process_url;
        }
        if (isset($args->co_process_uuid)) {
            $registro_fiduciario_parte_assinatura->co_process_uuid = $args->co_process_uuid;
        }

        if (!$registro_fiduciario_parte_assinatura->save()) {
            throw new Exception('Erro ao atualizar a assinatura da parte.');
        }

        $registro_fiduciario_parte_assinatura->refresh();

        return $registro_fiduciario_parte_assinatura;
    }
}
