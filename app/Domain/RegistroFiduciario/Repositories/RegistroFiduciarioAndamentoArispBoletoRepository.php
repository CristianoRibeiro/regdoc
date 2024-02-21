<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Carbon\Carbon;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoArispBoletoRepositoryInterface;

use App\Exceptions\RegdocException;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use App\Domain\Arisp\Models\arisp_boleto;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento_arisp_boleto;

class RegistroFiduciarioAndamentoArispBoletoRepository implements RegistroFiduciarioAndamentoArispBoletoRepositoryInterface
{
    public function inserir_relacao(registro_fiduciario_andamento $registro_fiduciario_andamento, arisp_boleto $arisp_boleto) : registro_fiduciario_andamento_arisp_boleto
    {
        $nova_relacao = new registro_fiduciario_andamento_arisp_boleto();
        $nova_relacao->id_registro_fiduciario_andamento = $registro_fiduciario_andamento->id_registro_fiduciario_andamento;
        $nova_relacao->id_arisp_boleto = $arisp_boleto->id_arisp_boleto;

        if (!$nova_relacao->save()) {
            throw new RegdocException('Erro ao salvar a relação do boleto.');
        }
        return $nova_relacao;
    }
}
