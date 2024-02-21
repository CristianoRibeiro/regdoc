<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Helper;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

class GetConfigsProtocolo
{

    /**
	 * @var PedidoServiceInterface
	 * @var ConfiguracaoPessoaServiceInterface
	 */
	private $PedidoServiceInterface;
	private $ConfiguracaoPessoaServiceInterface;

	/**
	 * GetConfigsProtocolo constructor.
	 * @param PedidoServiceInterface $PedidoServiceInterface
	 * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
	 */
	public function __construct(PedidoServiceInterface $PedidoServiceInterface,
								ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
	{
		$this->PedidoServiceInterface = $PedidoServiceInterface;
		$this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::User()->pedido_ativo) {
                $pedido = $this->PedidoServiceInterface->buscar(Auth::User()->pedido_ativo);

                if ($pedido) { 
                    $configuracao_pessoa = $this->ConfiguracaoPessoaServiceInterface->listar_array($pedido->id_pessoa_origem);
                    foreach ($configuracao_pessoa as $slug => $valor) {
                        config([
                            'protocolo.'.$slug => $valor
                        ]);
                    }
                }
            } else {
                return redirect('/protocolo/sair');
            }
        }

		return $next($request);
    }
}
