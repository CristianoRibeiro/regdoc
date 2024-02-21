<?php
namespace App\Http\Controllers\Protocolo;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hash;
use Auth;
use LogDB;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioServiceInterface;
use App\Domain\Registro\Contracts\RegistroProtocoloServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

class AcessarController extends Controller {

	/**
	 * @var PedidoServiceInterface
	 * @var PedidoUsuarioServiceInterface
	 */
	private $PedidoServiceInterface;
	private $PedidoUsuarioServiceInterface;

	/**
	 * AcessarController constructor.
	 * @param PedidoServiceInterface $PedidoServiceInterface
	 * @param PedidoUsuarioServiceInterface $PedidoUsuarioServiceInterface
	 */
	public function __construct(PedidoServiceInterface $PedidoServiceInterface,
								PedidoUsuarioServiceInterface $PedidoUsuarioServiceInterface,
				private	RegistroProtocoloServiceInterface $RegistroProtocoloService,
				ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
	{
		$this->PedidoServiceInterface = $PedidoServiceInterface;
		$this->PedidoUsuarioServiceInterface = $PedidoUsuarioServiceInterface;
		$this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
	}

	public function acessar(Request $request)
	{
		if (!$request->protocolo || !$request->senha) throw new \Exception('Protocolo e senha são obrigatórios.');

		$pedido = $this->PedidoServiceInterface->buscar_protocolo($request->protocolo);

		if (!$pedido) throw new \Exception('O protocolo digitado não foi encontrado.');
		if (count($pedido->pedido_usuario) === 0) throw new \Exception('Quantidade de usuarios no pedido é igual a zero.');
		
		$pedido_usuario_logado = null;
		foreach ($pedido->pedido_usuario as $pedido_usuario) {
			if (Hash::check($request->senha, $pedido_usuario->pedido_usuario_senha->senha)) {
				$pedido_usuario_logado = $pedido_usuario;
				break;
			}
		}

		if ($pedido_usuario_logado) {
			Auth::User()->pedido_ativo = $pedido->id_pedido;

			if (Auth::User()->pedido_ativo) { 
        $configuracao_pessoa = $this->ConfiguracaoPessoaServiceInterface->listar_array($pedido->id_pessoa_origem);
        foreach ($configuracao_pessoa as $slug => $valor) {
          config([
            'protocolo.'.$slug => $valor
          ]);
        }
      }
		}

		if (!$pedido_usuario_logado) throw new \Exception('Usuário não encontrado.');

		LogDB::insere(
			$pedido_usuario_logado->usuario->id_usuario,
			1,
			'Acesso via Protocolo e Senha realizado com sucesso.',
			'Acesso via Protocolo',
			'N',
			request()->ip()
		);

		return $this->RegistroProtocoloService->render_pedido($pedido_usuario);
	}

	public function acessar_token(Request $request)
  {
    if (!$request->token) throw new \Exception('Token não encontrado.');

		$pedido_usuario = $this->PedidoUsuarioServiceInterface->buscar_token($request->token);

		if ($pedido_usuario) {

      $usuario = $pedido_usuario->usuario;
      $pedido = $pedido_usuario->pedido;

      Auth::login($usuario);
			Auth::User()->pedido_ativo = $pedido->id_pedido;
			Auth::User()->pessoa_ativa = $usuario->usuario_pessoa[0]->pessoa;

			if (Auth::User()->pedido_ativo) { 
        $configuracao_pessoa = $this->ConfiguracaoPessoaServiceInterface->listar_array($pedido->id_pessoa_origem);
        foreach ($configuracao_pessoa as $slug => $valor) {
          config([
            'protocolo.'.$slug => $valor
          ]);
        }
      }
    }

		if (!$pedido_usuario) throw new \Exception('O token enviado não foi encontrado.');

		LogDB::insere(
			$pedido_usuario->usuario->id_usuario,
			1,
			'Acesso via Token realizado com sucesso.',
			'Acesso via Token',
			'N',
			request()->ip()
		);

		return $this->RegistroProtocoloService->render_pedido($pedido_usuario);
  }

	public function sair(Request $request)
	{
		Auth::logout();

			$request->session()->invalidate();

		return redirect('/');
	}
}
