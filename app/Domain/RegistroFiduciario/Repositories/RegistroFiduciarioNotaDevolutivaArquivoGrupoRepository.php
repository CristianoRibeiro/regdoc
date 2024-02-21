<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use Auth;
use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_arquivo_grupo;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface;

class RegistroFiduciarioNotaDevolutivaArquivoGrupoRepository implements RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_nota_devolutiva_arquivo_grupo::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva_arquivo_grupo) : ?registro_fiduciario_nota_devolutiva_arquivo_grupo
    {
        return registro_fiduciario_nota_devolutiva_arquivo_grupo::find($id_registro_fiduciario_nota_devolutiva_arquivo_grupo);
    }

     /**
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @throws Exception
     */
    public function inserir(stdClass $args) : registro_fiduciario_nota_devolutiva_arquivo_grupo
    {
        $nova_nota_devolutiva_arquivo_grupo = new registro_fiduciario_nota_devolutiva_arquivo_grupo();
        $nova_nota_devolutiva_arquivo_grupo->id_registro_fiduciario_nota_devolutiva = $args->id_registro_fiduciario_nota_devolutiva;
        $nova_nota_devolutiva_arquivo_grupo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $nova_nota_devolutiva_arquivo_grupo->id_usuario_cad = Auth::User()->id_usuario;
        if (!$nova_nota_devolutiva_arquivo_grupo->save()) {
            throw new Exception('Erro ao salvar o arquivo');
        }

        return $nova_nota_devolutiva_arquivo_grupo;
    }

    /**
     * @param registro_fiduciario_nota_devolutiva_arquivo_grupo $registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @throws Exception
     */
    public function alterar(registro_fiduciario_nota_devolutiva_arquivo_grupo $registro_fiduciario_nota_devolutiva_arquivo_grupo, stdClass $args) : registro_fiduciario_nota_devolutiva_arquivo_grupo
    {
        if (isset($args->id_registro_fiduciario_nota_devolutiva)) {
            $registro_fiduciario_nota_devolutiva_arquivo_grupo->id_registro_fiduciario_nota_devolutiva = $args->id_registro_fiduciario_nota_devolutiva;
        }
        if (isset($args->id_arquivo_grupo_produto)) {
            $registro_fiduciario_nota_devolutiva_arquivo_grupo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        }
        
        if (!$registro_fiduciario_nota_devolutiva_arquivo_grupo->save()) {
            throw new  Exception('Erro ao atualizar o arquivo.');
        }

        $registro_fiduciario_nota_devolutiva_arquivo_grupo->refresh();

        return $registro_fiduciario_nota_devolutiva_arquivo_grupo;
    }
}
