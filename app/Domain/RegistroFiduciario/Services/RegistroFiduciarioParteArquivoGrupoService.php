<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Exception;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteArquivoGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteArquivoGrupoServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_arquivo_grupo;

class RegistroFiduciarioParteArquivoGrupoService implements RegistroFiduciarioParteArquivoGrupoServiceInterface
{
    /**
     * @var RegistroFiduciarioParteArquivoGrupoRepositoryInterface
     */
    protected $RegistroFiduciarioParteArquivoGrupoRepositoryInterface;

    /**
     * RegistroFiduciarioParteArquivoGrupoService. constructor.
     * @param RegistroFiduciarioParteArquivoGrupoRepositoryInterface $RegistroFiduciarioParteArquivoGrupoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioParteArquivoGrupoRepositoryInterface $RegistroFiduciarioParteArquivoGrupoRepositoryInterface)
    {
        $this->RegistroFiduciarioParteArquivoGrupoRepositoryInterface = $RegistroFiduciarioParteArquivoGrupoRepositoryInterface;
    }

    /**
     * @param registro_fiduciario_parte_arquivo_grupo $registro_fiduciario_parte_arquivo_grupo
     * @param stdClass $args
     * @return registro_fiduciario_parte_arquivo_grupo
     * @throws Exception
     */
    public function alterar($registro_fiduciario_parte_arquivo_grupo, stdClass $args) : registro_fiduciario_parte_arquivo_grupo
    {
        return $this->RegistroFiduciarioParteArquivoGrupoRepositoryInterface->alterar($registro_fiduciario_parte_arquivo_grupo, $args);
    }
}
