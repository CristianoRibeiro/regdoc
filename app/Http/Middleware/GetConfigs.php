<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Helper;
use Carbon\Carbon;

use App\Domain\Pessoa\Models\pessoa;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

class GetConfigs
{
    /**
	 * @var PedidoServiceInterface
	 * @var ConfiguracaoPessoaServiceInterface
	 */
	private $PedidoServiceInterface;
	private $ConfiguracaoPessoaServiceInterface;

	/**
	 * GetConfigs constructor.
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
            if (Auth::User()->pessoa_ativa) {
                $pessoa = new pessoa();
                $pessoa = $pessoa->find(Auth::User()->pessoa_ativa->id_pessoa);

                // Atualizar a sessão do pessoa_ativa
                Auth::User()->pessoa_ativa = $pessoa;

                
                $configuracao_pessoa = $this->ConfiguracaoPessoaServiceInterface->listar_array($pessoa->id_pessoa);
                foreach ($configuracao_pessoa as $slug => $valor) {
                    config([
                        'global.'.$slug => $valor
                    ]);
                }
            } else {
                return redirect('/app/selecionar-entidade');
            }
		}

        return $next($request);
    }
}
