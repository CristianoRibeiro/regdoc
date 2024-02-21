<?php

namespace App\Http\Controllers\Inicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use stdClass;

use App\Domain\Usuario\Models\usuario;
use App\Domain\RegistroFiduciario\Models\situacao_pedido_grupo_produto;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

class RegistroOperadoresController extends Controller
{
    /**
     * @var RegistroFiduciarioServiceInterface
     *
     */
    protected $RegistroFiduciarioServiceInterface;

    /**
     * RegistroFiduciarioController constructor.
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     */
    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
    }

    public function index(Request $request)
    {
        $registros_usuarios_operadores = new usuario();
        $registros_usuarios_operadores = $registros_usuarios_operadores->select('usuario.*')
            ->join('registro_fiduciario_operador', function($join) {
                $join->on('registro_fiduciario_operador.id_usuario', 'usuario.id_usuario')
                    ->where('registro_fiduciario_operador.in_registro_ativo', 'S');
            })
            ->groupBy('usuario.id_usuario')
            ->orderBy('usuario.no_usuario')
            ->get();
            
        /*
         * A intenção aqui é listar a quantidade de pedidos de registros que não tem operadores vinculados, agrupados por situação,
         * para que seja possível listar na tela inicial na linha de "Sem operadores".
         * 
         * Ficou um pouco grande para que a demanda seja entregue rapidamente, mas pode ser pensada uma solução melhor.
         */
        $registros_sem_operadores_situacoes = new situacao_pedido_grupo_produto();
        $registros_sem_operadores_situacoes = $registros_sem_operadores_situacoes->select('situacao_pedido_grupo_produto.id_situacao_pedido_grupo_produto', DB::raw('count(pedido.id_pedido) as total_pedidos'))
            ->join('pedido', 'pedido.id_situacao_pedido_grupo_produto', '=', 'situacao_pedido_grupo_produto.id_situacao_pedido_grupo_produto')
            ->join('registro_fiduciario_pedido', 'registro_fiduciario_pedido.id_pedido', '=', 'pedido.id_pedido')
            ->join('registro_fiduciario', 'registro_fiduciario.id_registro_fiduciario', '=', 'registro_fiduciario_pedido.id_registro_fiduciario')
            ->leftJoin('registro_fiduciario_operador', function($join) {
                $join->on('registro_fiduciario_operador.id_registro_fiduciario', 'registro_fiduciario.id_registro_fiduciario')
                    ->where('registro_fiduciario_operador.in_registro_ativo', 'S');
            })
            ->where('situacao_pedido_grupo_produto.in_registro_ativo', '=', 'S')
            ->where('situacao_pedido_grupo_produto.id_grupo_produto', config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'))
            ->whereNull('registro_fiduciario_operador.id_registro_fiduciario_operador')
            ->whereNotIn('pedido.id_situacao_pedido_grupo_produto', [config('constants.SITUACAO.11.ID_CANCELADO')])
            ->groupBy('situacao_pedido_grupo_produto.id_situacao_pedido_grupo_produto')
            ->get()
            ->keyBy('id_situacao_pedido_grupo_produto')
            ->transform(function ($situacao_pedido_grupo_produto) {
                return $situacao_pedido_grupo_produto->total_pedidos;
            })
            ->toArray();

        $registros_sem_operadores['nota_devolutiva'] = $registros_sem_operadores_situacoes[config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')] ?? 0;
        unset($registros_sem_operadores_situacoes[config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')]);
        $registros_sem_operadores['finalizado'] = ($registros_sem_operadores_situacoes[config('constants.SITUACAO.11.ID_REGISTRADO')] ?? 0) + ($registros_sem_operadores_situacoes[config('constants.SITUACAO.11.ID_FINALIZADO')] ?? 0);
        unset($registros_sem_operadores_situacoes[config('constants.SITUACAO.11.ID_REGISTRADO')]);
        unset($registros_sem_operadores_situacoes[config('constants.SITUACAO.11.ID_FINALIZADO')]);
        $registros_sem_operadores['em_andamento'] = array_sum($registros_sem_operadores_situacoes);

        // Argumentos para o retorno da view
        $compact_args = [
            'registros_usuarios_operadores' => $registros_usuarios_operadores,
            'registros_sem_operadores' => $registros_sem_operadores
        ];

		return view('app.inicio.operadores.registro-fiduciario.geral-operadores-registros', $compact_args);
    }

    public function detalhes(Request $request)
    {
        // Montagem dos filtros
        $filtros = new stdClass();
        $filtros->id_usuario_operador = $request->id_usuario_operador;

        $todos_registros = $this->RegistroFiduciarioServiceInterface->listar($filtros);

        $total_registros = clone $todos_registros;
        $total_registros = $total_registros->get()->count();

        $todos_registros = $todos_registros->limit(100)
            ->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'total_registros' => $total_registros,
            'todos_registros' => $todos_registros
        ];
        return view('app.inicio.operadores.registro-fiduciario.geral-operadores-registros-detalhes', $compact_args);
    }
}
