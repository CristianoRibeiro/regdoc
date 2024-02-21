<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Services;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoServiceInterface;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo;

class RegistroFiduciarioParteAssinaturaArquivoService implements RegistroFiduciarioParteAssinaturaArquivoServiceInterface
{
    /**
     * @var RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface
     */
    protected $RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface;

    /**
     * RegistroFiduciarioParteAssinaturaArquivoService constructor.
     * @param RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface $RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface $RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface)
    {
        $this->RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface = $RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface;
    }

    /**
     * @param int $registro_fiduciario_parte_assinatura_arquivo
     * @return registro_fiduciario_parte_assinatura_arquivo|null
     */
    public function buscar(int $registro_fiduciario_parte_assinatura_arquivo): ?registro_fiduciario_parte_assinatura_arquivo
    {
        return $this->RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface->buscar($registro_fiduciario_parte_assinatura_arquivo);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura_arquivo
     */
    public function inserir(stdClass $args): registro_fiduciario_parte_assinatura_arquivo
    {
        return $this->RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_parte_assinatura_arquivo $registro_fiduciario_parte_assinatura_arquivo
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura_arquivo
     */
    public function alterar(registro_fiduciario_parte_assinatura_arquivo $registro_fiduciario_parte_assinatura_arquivo, stdClass $args): registro_fiduciario_parte_assinatura_arquivo
    {
        return $this->RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface->alterar($registro_fiduciario_parte_assinatura_arquivo, $args);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura_arquivo
     */
    public function buscar_alterar(stdClass $args): registro_fiduciario_parte_assinatura_arquivo
    {
        $registro_fiduciario_parte_assinatura_arquivo = $this->buscar($args->id_registro_fiduciario_parte_assinatura_arquivo);
        if (!$registro_fiduciario_parte_assinatura_arquivo)
            throw new Exception('O arquivo da assinatura não foi encontrada');

        return $this->alterar($registro_fiduciario_parte_assinatura_arquivo, $args);
    }
}
