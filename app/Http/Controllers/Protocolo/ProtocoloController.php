<?php
namespace App\Http\Controllers\Protocolo;

use App\Http\Controllers\Controller;

use App\Domain\Pedido\Contracts\PedidoUsuarioServiceInterface;

use App\Domain\Registro\Contracts\RegistroProtocoloServiceInterface;

use Illuminate\Contracts\Session\Session;

class ProtocoloController extends Controller
{
	private RegistroProtocoloServiceInterface $RegistroProtocoloService;

	public function __construct(private PedidoUsuarioServiceInterface $PedidoUsuarioServiceInterface,
															RegistroProtocoloServiceInterface $RegistroProtocoloService)
	{
		$this->RegistroProtocoloService = $RegistroProtocoloService;
	}

	public function index(Session $session)
	{
		if (!$session->get('pedido_usuario_id')) throw new \Exception('Pedido usuário não existente.');
		
		$pedido_usuario = $this->PedidoUsuarioServiceInterface->buscar($session->get('pedido_usuario_id'));
		if(!$pedido_usuario) throw new \Exception('Pedido usuário não existente.');

		return $this->RegistroProtocoloService->render_pedido($pedido_usuario);
	}
}