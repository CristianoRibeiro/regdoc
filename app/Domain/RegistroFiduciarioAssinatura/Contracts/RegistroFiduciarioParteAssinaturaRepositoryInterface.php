<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Contracts;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura;

interface RegistroFiduciarioParteAssinaturaRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_parte_assinatura
     * @return registro_fiduciario_parte_assinatura|null
     */
    public function buscar(int $id_registro_fiduciario_parte_assinatura): ?registro_fiduciario_parte_assinatura;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura
     */
    public function inserir(stdClass $args) : registro_fiduciario_parte_assinatura;

    /**
     * @param registro_fiduciario_parte_assinatura $registro_fiduciario_parte_assinatura
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura
     */
    public function alterar(registro_fiduciario_parte_assinatura $registro_fiduciario_parte_assinatura, stdClass $args): registro_fiduciario_parte_assinatura;
}
