<?php

namespace App\Domain\Pedido\Services;

use stdClass;
use Ramsey\Uuid\Uuid;

use App\Domain\Pedido\Models\pedido_usuario;

use App\Domain\Pedido\Contracts\PedidoUsuarioRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioSenhaRepositoryInterface;

class PedidoUsuarioService implements PedidoUsuarioServiceInterface
{
    /**
     * @var PedidoUsuarioRepositoryInterface
     * @var UsuarioServiceInterface
     * @var PedidoUsuarioSenhaRepositoryInterface
     */
    protected $PedidoUsuarioRepositoryInterface;
    protected $UsuarioServiceInterface;
    protected $PedidoUsuarioSenhaRepositoryInterface;

    /**
     * PedidoUsuarioService constructor.
     * @param PedidoUsuarioRepositoryInterface $PedidoUsuarioRepositoryInterface
     * @param UsuarioServiceInterface $UsuarioServiceInterface
     * @param PedidoUsuarioSenhaRepositoryInterface $PedidoUsuarioSenhaRepositoryInterface
     */
    public function __construct(PedidoUsuarioRepositoryInterface $PedidoUsuarioRepositoryInterface,
        UsuarioServiceInterface $UsuarioServiceInterface,
        PedidoUsuarioSenhaRepositoryInterface $PedidoUsuarioSenhaRepositoryInterface)
    {
        $this->PedidoUsuarioRepositoryInterface = $PedidoUsuarioRepositoryInterface;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;
        $this->PedidoUsuarioSenhaRepositoryInterface = $PedidoUsuarioSenhaRepositoryInterface;
    }

    /**
     * @param int $id_pedido_usuario
     * @return pedido_usuario|null
     */
    public function buscar(int $id_pedido_usuario) : ?pedido_usuario
    {
        return $this->PedidoUsuarioRepositoryInterface->buscar($id_pedido_usuario);
    }

    /**
     * @param string $token
     * @return pedido_usuario|null
     */
    public function buscar_token(string $token) : ?pedido_usuario
    {
        return $this->PedidoUsuarioRepositoryInterface->buscar_token($token);
    }

    /**
     * @param stdClass $args
     * @return pedido_usuario
     */
    public function inserir(stdClass $args): pedido_usuario
    {
        /**
         * Insere o usuário como uma parte (cliente), essa inserção inclui a
         * inserção da pessoa também.
         */
        $args_novo_usuario = new stdClass();
        $args_novo_usuario->no_usuario = $args->no_contato;
        $args_novo_usuario->email_usuario = $args->no_email_contato;
        $args_novo_usuario->nu_cpf_cnpj = $args->nu_cpf_cnpj;

        $novo_usuario = $this->UsuarioServiceInterface->inserir_parte($args_novo_usuario);

        /**
         * Insere o vínculo entre o pedido e o usuário.
         */
        $args_novo_pedido_usuario = new stdClass();
        $args_novo_pedido_usuario->id_pedido = $args->id_pedido;
        $args_novo_pedido_usuario->id_usuario = $novo_usuario->id_usuario;
        $args_novo_pedido_usuario->token = Uuid::uuid4();

        $novo_pedido_usuario = $this->PedidoUsuarioRepositoryInterface->inserir($args_novo_pedido_usuario);

        /**
         * Insere a senha para o vínculo entre o pedido e o usuário.
         */
        $args_pedido_usuario_senha = new stdClass();
        $args_pedido_usuario_senha->id_pedido_usuario = $novo_pedido_usuario->id_pedido_usuario;
        $args_pedido_usuario_senha->senha = $args->senha;

        $this->PedidoUsuarioSenhaRepositoryInterface->inserir($args_pedido_usuario_senha);

        return $novo_pedido_usuario;
    }
}
