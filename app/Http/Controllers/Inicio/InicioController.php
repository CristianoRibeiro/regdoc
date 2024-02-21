<?php

namespace App\Http\Controllers\Inicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

use App\Domain\RegistroFiduciario\Models\situacao_pedido_grupo_produto;

class InicioController extends Controller
{
	public function index(Request $request)
	{
		$total_pedidos = new situacao_pedido_grupo_produto();
        $total_pedidos = $total_pedidos->join('pedido','pedido.id_situacao_pedido_grupo_produto', '=', 'situacao_pedido_grupo_produto.id_situacao_pedido_grupo_produto')
             ->where('situacao_pedido_grupo_produto.in_registro_ativo', 'S');

		switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 8:
                $total_pedidos = $total_pedidos->join('pedido_pessoa', function ($join) {
                    $join->on('pedido_pessoa.id_pedido', '=', 'pedido.id_pedido')
                    	->where('pedido_pessoa.id_pessoa', Auth::User()->pessoa_ativa->id_pessoa);
                });
                break;
            default:
                break;
        }

		$total_pedidos_fiduciario = clone $total_pedidos;
		$total_pedidos_fiduciario = $total_pedidos_fiduciario->where('id_produto', config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
			->count();

		$total_pedidos_garantias = clone $total_pedidos;
		$total_pedidos_garantias = $total_pedidos_garantias->where('id_produto', config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
			->count();

		$total_pedidos_documentos = clone $total_pedidos;
		$total_pedidos_documentos = $total_pedidos_documentos->where('id_produto', config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO'))
			->count();

        // Argumentos para o retorno da view
        $compact_args = [
            'total_pedidos_fiduciario' => $total_pedidos_fiduciario,
            'total_pedidos_garantias' => $total_pedidos_garantias,
            'total_pedidos_documentos' => $total_pedidos_documentos
        ];

		return view('app.inicio.inicio', $compact_args);
	}
}
