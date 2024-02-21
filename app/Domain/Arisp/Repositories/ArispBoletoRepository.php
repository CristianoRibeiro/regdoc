<?php

namespace App\Domain\Arisp\Repositories;

use Auth;
use stdClass;
use Exception;

use App\Domain\Arisp\Models\arisp_boleto;

use App\Domain\Arisp\Contracts\ArispBoletoRepositoryInterface;

class ArispBoletoRepository implements ArispBoletoRepositoryInterface
{
    /**
     * @param int $id_arisp_boleto
     * @return arisp_boleto|null
     */
    public function buscar(int $id_arisp_boleto) : ?arisp_boleto
    {
        return arisp_boleto::find($id_arisp_boleto);
    }

    /**
     * @param string $url_boleto
     * @return arisp_boleto|null
     */
    public function buscar_url(string $url_boleto) : ?arisp_boleto
    {
        return arisp_boleto::where('url_boleto', $url_boleto)->first();
    }

    /**
     * @param stdClass $args
     * @return arisp_boleto
     */
    public function inserir(stdClass $args) : arisp_boleto
    {
        $novo_arisp_boleto = new arisp_boleto();
        $novo_arisp_boleto->id_arisp_pedido = $args->id_arisp_pedido;
        $novo_arisp_boleto->url_boleto = $args->url_boleto;
        $novo_arisp_boleto->dt_boleto = $args->dt_boleto;
        $novo_arisp_boleto->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();

        if (!$novo_arisp_boleto->save()) {
            throw new Exception('Erro ao salvar o boleto.');
        }

        return $novo_arisp_boleto;
    }
}
