<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Contracts;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo;

interface RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_parte_assinatura_arquivo
     * @return registro_fiduciario_parte_assinatura_arquivo|null
     */
    public function buscar(int $id_registro_fiduciario_parte_assinatura_arquivo): ?registro_fiduciario_parte_assinatura_arquivo;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura_arquivo
     */
    public function inserir(stdClass $args) : registro_fiduciario_parte_assinatura_arquivo;

    /**
     * @param registro_fiduciario_parte_assinatura_arquivo $registro_fiduciario_parte_assinatura_arquivo
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura_arquivo
     */
    public function alterar(registro_fiduciario_parte_assinatura_arquivo $registro_fiduciario_parte_assinatura_arquivo, stdClass $args): registro_fiduciario_parte_assinatura_arquivo;
}
