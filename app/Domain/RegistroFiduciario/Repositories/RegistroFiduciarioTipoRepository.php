<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Auth;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioTipoRepositoryInterface;

class RegistroFiduciarioTipoRepository implements RegistroFiduciarioTipoRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_tipo
     * @return registro_fiduciario_tipo
     */
    public function buscar(int $id_registro_fiduciario_tipo) : registro_fiduciario_tipo
    {
        return registro_fiduciario_tipo::findOrFail($id_registro_fiduciario_tipo);
    }

    /**
     * @param int $id_produto
     * @return Collection
     */
    public function tipos_registro(int $id_produto) : Collection
    {
        $registro_fiduciario_tipo = registro_fiduciario_tipo::where('registro_fiduciario_tipo.in_registro_ativo', 'S')
                                                            ->where('id_produto', $id_produto);
        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 8:
                $registro_fiduciario_tipo = $registro_fiduciario_tipo->join('registro_fiduciario_tipo_pessoa', function ($join) {
                    $join->on('registro_fiduciario_tipo_pessoa.id_registro_fiduciario_tipo', '=', 'registro_fiduciario_tipo.id_registro_fiduciario_tipo')
                         ->where('registro_fiduciario_tipo_pessoa.id_pessoa', Auth::User()->pessoa_ativa->id_pessoa);
                });
                break;
        }

        return $registro_fiduciario_tipo->get();
    }
}
