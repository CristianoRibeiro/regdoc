<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCanalPdvRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_canal_pdv_parceiro;

use App\Exceptions\RegdocException;

use Illuminate\Support\Facades\Auth;

use stdClass;

class RegistroFiduciarioCanalPdvRepository implements RegistroFiduciarioCanalPdvRepositoryInterface
{
    public function inserir(stdClass $args) : registro_fiduciario_canal_pdv_parceiro
    {
        $nova_relacao = new registro_fiduciario_canal_pdv_parceiro();
        $nova_relacao->id_registro_fiduciario = $args->id_registro_fiduciario;
        $nova_relacao->id_canal_pdv_parceiro = $args->id_canal_pdv_parceiro;
        $nova_relacao->no_pj = $args->no_pj;
        $nova_relacao->id_usuario_cad = Auth::User()->id_usuario;

        if (!$nova_relacao->save()) {
            throw new RegdocException('Erro ao salvar a relação do canal.');
        }

        return $nova_relacao;
    }
}