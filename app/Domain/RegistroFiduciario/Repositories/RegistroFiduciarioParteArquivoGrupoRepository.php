<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Exception;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteArquivoGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_arquivo_grupo;

class RegistroFiduciarioParteArquivoGrupoRepository implements RegistroFiduciarioParteArquivoGrupoRepositoryInterface
{

    /**
     * @param registro_fiduciario_parte_arquivo_grupo $registro_fiduciario_parte_arquivo_grupo
     * @param stdClass $args
     * @return registro_fiduciario_parte_arquivo_grupo
     * @throws Exception
     */
    public function alterar($registro_fiduciario_parte_arquivo_grupo, stdClass $args) : registro_fiduciario_parte_arquivo_grupo
    {
        if(isset($args->in_registro_ativo)) {
            $registro_fiduciario_parte_arquivo_grupo->in_registro_ativo = $args->in_registro_ativo;
        }
        if (!$registro_fiduciario_parte_arquivo_grupo->save()) {
            throw new Exception('Erro ao atualizar o arquivo do contrato do registro.');
        }

        $registro_fiduciario_parte_arquivo_grupo->refresh();

        return $registro_fiduciario_parte_arquivo_grupo;
    }
}
