<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Contracts;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura;

interface RegistroFiduciarioAssinaturaRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_assinatura
     * @return registro_fiduciario_assinatura|null
     */
    public function buscar(int $id_registro_fiduciario_assinatura): ?registro_fiduciario_assinatura;

    /**
     * @param string $uuid
     * @return registro_fiduciario_assinatura|null
     */
    public function buscar_pdavh_uuid(string $uuid): ?registro_fiduciario_assinatura;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     */
    public function inserir(stdClass $args) : registro_fiduciario_assinatura;

    /**
     * @param registro_fiduciario_assinatura $registro_fiduciario_assinatura
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     */
    public function alterar(registro_fiduciario_assinatura $registro_fiduciario_assinatura, stdClass $args): registro_fiduciario_assinatura;
}
