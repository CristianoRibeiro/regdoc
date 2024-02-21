<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura;
use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura;

interface RegistroFiduciarioParteAssinaturaServiceInterface
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

    /**
     * @param registro_fiduciario_assinatura $registro_fiduciario_assinatura
     * @param Collection $arquivos
     * @param int $id_registro_fiduciario_parte
     * @param int|null $id_registro_fiduciario_procurador
     * @return registro_fiduciario_parte_assinatura
     */
    public function inserir_parte_assinatura(registro_fiduciario_assinatura $registro_fiduciario_assinatura, Collection $arquivos, array $associacao_arquivos_partes, int $id_registro_fiduciario_parte, ?int $id_registro_fiduciario_procurador, int $nu_ordem_assinatura) : registro_fiduciario_parte_assinatura;
}
