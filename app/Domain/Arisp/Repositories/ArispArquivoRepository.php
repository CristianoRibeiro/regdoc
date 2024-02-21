<?php

namespace App\Domain\Arisp\Repositories;

use Auth;
use stdClass;

use App\Domain\Arisp\Models\arisp_arquivo;

use App\Domain\Arisp\Contracts\ArispArquivoRepositoryInterface;

class ArispArquivoRepository implements ArispArquivoRepositoryInterface
{
    /**
     * @param int $id_arisp_arquivo
     * @return arisp_arquivo|null
     */
    public function buscar(int $id_arisp_arquivo) : ?arisp_arquivo
    {
        return arisp_arquivo::find($id_arisp_arquivo);
    }

    /**
     * @param stdClass $args
     * @return arisp_arquivo
     */
    public function inserir(stdClass $args) : arisp_arquivo
    {
        $novo_arisp_arquivo = new arisp_arquivo();
        $novo_arisp_arquivo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $novo_arisp_arquivo->codigo_arquivo = $args->codigo_arquivo;
        $novo_arisp_arquivo->id_usuario_cad = Auth::User()->id_usuario ?? 1;

        if (!$novo_arisp_arquivo->save()) {
            throw new Exception('Erro ao salvar o arquivo.');
        }

        return $novo_arisp_arquivo;
    }
}
