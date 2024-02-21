<?php

namespace App\Domain\Arisp\Repositories;

use Auth;
use stdClass;
use Exception;

use App\Domain\Arisp\Models\arisp_anexo;

use App\Domain\Arisp\Contracts\ArispAnexoRepositoryInterface;

class ArispAnexoRepository implements ArispAnexoRepositoryInterface
{
    /**
     * @param int $id_arisp_anexo
     * @return arisp_anexo|null
     */
    public function buscar(int $id_arisp_anexo) : ?arisp_anexo
    {
        return arisp_anexo::find($id_arisp_anexo);
    }

    /**
     * @param string $codigo_anexo
     * @return arisp_anexo|null
     */
    public function buscar_codigo(string $codigo_anexo) : ?arisp_anexo
    {
        return arisp_anexo::where('codigo_anexo', $codigo_anexo)->first();
    }

    /**
     * @param stdClass $args
     * @return arisp_anexo
     */
    public function inserir(stdClass $args) : arisp_anexo
    {
        $novo_arisp_anexo = new arisp_anexo();
        $novo_arisp_anexo->id_arisp_pedido = $args->id_arisp_pedido;
        $novo_arisp_anexo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $novo_arisp_anexo->id_arisp_anexo_tipo = $args->id_arisp_anexo_tipo;
        $novo_arisp_anexo->descricao = $args->descricao;
        $novo_arisp_anexo->nome_anexo = $args->nome_anexo;
        $novo_arisp_anexo->codigo_anexo = $args->codigo_anexo;
        $novo_arisp_anexo->url_anexo = $args->url_anexo;
        $novo_arisp_anexo->dt_anexo = $args->dt_anexo;
        $novo_arisp_anexo->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();

        if (!$novo_arisp_anexo->save()) {
            throw new RegdocException('Erro ao salvar o anexo.');
        }

        return $novo_arisp_anexo;
    }
}
