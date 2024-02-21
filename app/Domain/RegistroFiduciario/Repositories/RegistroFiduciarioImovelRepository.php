<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;
use Exception;
use Ramsey\Uuid\Uuid;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel;

class RegistroFiduciarioImovelRepository implements RegistroFiduciarioImovelRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_imovel
     * @param bool $retornar_endereco
     * @return registro_fiduciario_imovel|null
     */
    public function buscar(int $id_registro_fiduciario_imovel, bool $retornar_endereco = false) : ?registro_fiduciario_imovel
    {
        $registro_fiduciario_imovel = registro_fiduciario_imovel::where('id_registro_fiduciario_imovel', $id_registro_fiduciario_imovel);
        if ($retornar_endereco) {
            $registro_fiduciario_imovel = $registro_fiduciario_imovel->with('endereco');
        }

        return $registro_fiduciario_imovel->first();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_imovel
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_imovel
    {
        $novo_registro_fiduciario_imovel = new registro_fiduciario_imovel();
        $novo_registro_fiduciario_imovel->uuid = Uuid::uuid4();
        $novo_registro_fiduciario_imovel->id_registro_fiduciario = $args->id_registro_fiduciario;
        $novo_registro_fiduciario_imovel->id_registro_fiduciario_imovel_tipo = $args->id_registro_fiduciario_imovel_tipo ?? NULL;
        $novo_registro_fiduciario_imovel->id_registro_fiduciario_imovel_localizacao = $args->id_registro_fiduciario_imovel_localizacao ?? NULL;
        $novo_registro_fiduciario_imovel->id_registro_fiduciario_imovel_livro = $args->id_registro_fiduciario_imovel_livro ?? NULL;
        $novo_registro_fiduciario_imovel->id_registro_fiduciario_endereco = $args->id_registro_fiduciario_endereco ?? NULL;
        $novo_registro_fiduciario_imovel->nu_matricula = $args->nu_matricula;
        $novo_registro_fiduciario_imovel->nu_iptu = $args->nu_iptu ?? NULL;
        $novo_registro_fiduciario_imovel->nu_ccir = $args->nu_ccir ?? NULL;
        $novo_registro_fiduciario_imovel->nu_nirf = $args->nu_nirf ?? NULL;
        $novo_registro_fiduciario_imovel->va_compra_venda = $args->va_compra_venda ?? NULL;
        $novo_registro_fiduciario_imovel->va_venal = $args->va_venal ?? NULL;
        $novo_registro_fiduciario_imovel->id_usuario_cad = Auth::User()->id_usuario;
        if (!$novo_registro_fiduciario_imovel->save()) {
            throw new Exception('Erro ao salvar o imóvel do registro fiduciário.');
        }

        return $novo_registro_fiduciario_imovel;
    }

    /**
     * @param registro_fiduciario_imovel $registro_fiduciario_imovel
     * @param stdClass $args
     * @return registro_fiduciario_imovel
     * @throws Exception
     */
    public function alterar(registro_fiduciario_imovel $registro_fiduciario_imovel, stdClass $args) : registro_fiduciario_imovel
    {
        if (isset($args->id_registro_fiduciario_imovel_tipo)) {
            $registro_fiduciario_imovel->id_registro_fiduciario_imovel_tipo = $args->id_registro_fiduciario_imovel_tipo;
        }
        if (isset($args->id_registro_fiduciario_imovel_localizacao)) {
            $registro_fiduciario_imovel->id_registro_fiduciario_imovel_localizacao = $args->id_registro_fiduciario_imovel_localizacao;
        }
        if (isset($args->id_registro_fiduciario_imovel_livro)) {
            $registro_fiduciario_imovel->id_registro_fiduciario_imovel_livro = $args->id_registro_fiduciario_imovel_livro;
        }
        if (isset($args->nu_matricula)) {
            $registro_fiduciario_imovel->nu_matricula = $args->nu_matricula;
        }
        if (isset($args->nu_iptu)) {
            $registro_fiduciario_imovel->nu_iptu = $args->nu_iptu;
        }
        if (isset($args->nu_ccir)) {
            $registro_fiduciario_imovel->nu_ccir = $args->nu_ccir;
        }
        if (isset($args->nu_nirf)) {
            $registro_fiduciario_imovel->nu_nirf = $args->nu_nirf;
        }
        if (isset($args->va_compra_venda)) {
            $registro_fiduciario_imovel->va_compra_venda = $args->va_compra_venda;
        }
        if (isset($args->va_venal)) {
            $registro_fiduciario_imovel->va_venal = $args->va_venal;
        }

        if (!$registro_fiduciario_imovel->save()) {
            throw new Exception('Erro ao salvar o imóvel do registro fiduciário.');
        }

        return $registro_fiduciario_imovel;
    }

    /**
     * @param registro_fiduciario_imovel $registro_fiduciario_imovel
     * @return bool
     * @throws Exception
     */
    public function deletar(registro_fiduciario_imovel $registro_fiduciario_imovel) : bool
    {
        return $registro_fiduciario_imovel->delete();
    }
}
