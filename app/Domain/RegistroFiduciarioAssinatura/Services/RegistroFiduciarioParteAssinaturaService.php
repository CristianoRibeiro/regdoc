<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoServiceInterface;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura;
use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura;

class RegistroFiduciarioParteAssinaturaService implements RegistroFiduciarioParteAssinaturaServiceInterface
{
    /**
     * @var RegistroFiduciarioParteAssinaturaRepositoryInterface
     * @var RegistroFiduciarioParteAssinaturaArquivoServiceInterface
     */
    protected $RegistroFiduciarioParteAssinaturaRepositoryInterface;
    protected $RegistroFiduciarioParteAssinaturaArquivoServiceInterface;

    /**
     * RegistroFiduciarioParteAssinaturaService constructor.
     * @param RegistroFiduciarioParteAssinaturaRepositoryInterface $RegistroFiduciarioParteAssinaturaRepositoryInterface
     * @param RegistroFiduciarioParteAssinaturaArquivoServiceInterface $RegistroFiduciarioParteAssinaturaArquivoServiceInterface
     */
    public function __construct(RegistroFiduciarioParteAssinaturaRepositoryInterface $RegistroFiduciarioParteAssinaturaRepositoryInterface,
        RegistroFiduciarioParteAssinaturaArquivoServiceInterface $RegistroFiduciarioParteAssinaturaArquivoServiceInterface)
    {
        $this->RegistroFiduciarioParteAssinaturaRepositoryInterface = $RegistroFiduciarioParteAssinaturaRepositoryInterface;
        $this->RegistroFiduciarioParteAssinaturaArquivoServiceInterface = $RegistroFiduciarioParteAssinaturaArquivoServiceInterface;
    }

    /**
     * @param int $registro_fiduciario_parte_assinatura
     * @return registro_fiduciario_parte_assinatura|null
     */
    public function buscar(int $registro_fiduciario_parte_assinatura): ?registro_fiduciario_parte_assinatura
    {
        return $this->RegistroFiduciarioParteAssinaturaRepositoryInterface->buscar($registro_fiduciario_parte_assinatura);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura
     */
    public function inserir(stdClass $args): registro_fiduciario_parte_assinatura
    {
        return $this->RegistroFiduciarioParteAssinaturaRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_parte_assinatura $registro_fiduciario_parte_assinatura
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura
     */
    public function alterar(registro_fiduciario_parte_assinatura $registro_fiduciario_parte_assinatura, stdClass $args): registro_fiduciario_parte_assinatura
    {
        return $this->RegistroFiduciarioParteAssinaturaRepositoryInterface->alterar($registro_fiduciario_parte_assinatura, $args);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura
     */
    public function buscar_alterar(stdClass $args): registro_fiduciario_parte_assinatura
    {
        $registro_fiduciario_parte_assinatura = $this->buscar($args->id_registro_fiduciario_parte_assinatura);
        if (!$registro_fiduciario_parte_assinatura)
            throw new Exception('A assinatura da parte não foi encontrada');

        return $this->alterar($registro_fiduciario_parte_assinatura, $args);
    }

    /**
     * @param registro_fiduciario_assinatura $registro_fiduciario_assinatura
     * @param Collection $arquivos
     * @param int $id_registro_fiduciario_parte
     * @param int|null $id_registro_fiduciario_procurador
     * @return registro_fiduciario_parte_assinatura
     */
    public function inserir_parte_assinatura(registro_fiduciario_assinatura $registro_fiduciario_assinatura, Collection $arquivos, array $associacao_arquivos_partes = [], int $id_registro_fiduciario_parte, ?int $id_registro_fiduciario_procurador = NULL, int $nu_ordem_assinatura = 0) : registro_fiduciario_parte_assinatura
    {
        $args_nova_parte_assinatura = new stdClass();
        $args_nova_parte_assinatura->id_registro_fiduciario_assinatura = $registro_fiduciario_assinatura->id_registro_fiduciario_assinatura;
        $args_nova_parte_assinatura->id_registro_fiduciario_parte = $id_registro_fiduciario_parte;
        $args_nova_parte_assinatura->id_registro_fiduciario_procurador = $id_registro_fiduciario_procurador ?? NULL;
        $args_nova_parte_assinatura->nu_ordem_assinatura = $nu_ordem_assinatura;

        $nova_parte_assinatura = $this->inserir($args_nova_parte_assinatura);

        foreach ($arquivos as $arquivo) {
            if (count($associacao_arquivos_partes)>0) {
                $vincular_arquivo = false;
                if (isset($associacao_arquivos_partes[$arquivo->id_arquivo_grupo_produto])) {
                    if (in_array($id_registro_fiduciario_parte,
                        ($associacao_arquivos_partes[$arquivo->id_arquivo_grupo_produto] ?? []))) {
                            $vincular_arquivo = true;
                    }
                } else {
                    $vincular_arquivo = true;
                }
            } else {
                $vincular_arquivo = true;
            }

            if ($vincular_arquivo) {
                $args_nova_parte_assinatura_arquivo = new stdClass();
                $args_nova_parte_assinatura_arquivo->id_registro_fiduciario_parte_assinatura = $nova_parte_assinatura->id_registro_fiduciario_parte_assinatura;
                $args_nova_parte_assinatura_arquivo->id_arquivo_grupo_produto = $arquivo->id_arquivo_grupo_produto;

                $this->RegistroFiduciarioParteAssinaturaArquivoServiceInterface->inserir($args_nova_parte_assinatura_arquivo);
            }
        }

        return $nova_parte_assinatura;
    }
}
