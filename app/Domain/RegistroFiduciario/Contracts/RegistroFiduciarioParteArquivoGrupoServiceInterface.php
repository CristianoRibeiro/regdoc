<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Exception;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_arquivo_grupo;

interface RegistroFiduciarioParteArquivoGrupoServiceInterface
{
    /**
     * @param registro_fiduciario_parte_arquivo_grupo $registro_fiduciario_parte_arquivo_grupo
     * @param stdClass $args
     * @return registro_fiduciario_parte_arquivo_grupo
     * @throws Exception
     */
    public function alterar($registro_fiduciario_parte_arquivo_grupo, stdClass $args) : registro_fiduciario_parte_arquivo_grupo;
}
