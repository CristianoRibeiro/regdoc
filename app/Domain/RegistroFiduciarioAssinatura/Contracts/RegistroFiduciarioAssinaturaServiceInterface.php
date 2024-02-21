<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Contracts;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

interface RegistroFiduciarioAssinaturaServiceInterface
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

    /**
     * @param stdClass $args
     * @return registro_fiduciario_assinatura
     */
    public function buscar_alterar(stdClass $args): registro_fiduciario_assinatura;

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @param int $id_tipo_arquivo_grupo_produto
     * @param int $id_registro_fiduciario_assinatura_tipo
     * @param array $tipos_partes
     * @param array $partes
     * @return registro_fiduciario_assinatura
     */
    public function inserir_assinatura(registro_fiduciario $registro_fiduciario, int $id_tipo_arquivo_grupo_produto, int $id_registro_fiduciario_assinatura_tipo, array $tipos_partes = [], array $partes_ids = [], array $arquivos_ids = [], array $associacao_arquivos_partes = []) : registro_fiduciario_assinatura;
}
