<?php

namespace App\Http\Controllers\Registros;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use LogDB;
use stdClass;
use Mail;
use Carbon\Carbon;

use App\Http\Requests\RegistroFiduciario\Operadores\StoreOperadorRegistroFiduciario;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperadorServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;

use App\Mail\Registros\NotificacaoOperadorRegistroFiduciarioMail;

class RegistroFiduciarioOperadorController extends Controller
{
    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioOperadorServiceInterface
     * @var PessoaServiceInterface
     * @var UsuarioServiceInterface
     */
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioOperadorServiceInterface;
    protected $PessoaServiceInterface;
    protected $UsuarioServiceInterface;

    /**
     * RegistroFiduciarioOperadorController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioOperadorServiceInterface $RegistroFiduciarioOperadorServiceInterface
     * @param PessoaServiceInterface $PessoaServiceInterface
     * @param UsuarioServiceInterface $UsuarioServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
        RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
        RegistroFiduciarioOperadorServiceInterface $RegistroFiduciarioOperadorServiceInterface,
        PessoaServiceInterface $PessoaServiceInterface,
        UsuarioServiceInterface $UsuarioServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioOperadorServiceInterface = $RegistroFiduciarioOperadorServiceInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        $pessoas = $this->PessoaServiceInterface->listar_por_tipo([1, 13]);

        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'pessoas' => $pessoas,
        ];
        return view('app.produtos.registro-fiduciario.detalhes.operadores.geral-registro-operadores-detalhes', $compact_args);
    }


    /**
     * @param StoreOperadorRegistroFiduciario $request
     */
    public function store(StoreOperadorRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            foreach($request->id_usuario as $id_usuario) {
                $usuario = $this->UsuarioServiceInterface->buscar($id_usuario);

                $args_operador = new stdClass();
                $args_operador->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                $args_operador->id_usuario = $usuario->id_usuario;

                $registro_fiduciario_operador = $this->RegistroFiduciarioOperadorServiceInterface->inserir($args_operador);

                $assunto = 'Você foi vinculado como operador(a) ao registro ' . $pedido->protocolo_pedido;
                $mensagem = 'O seu usuário foi vinculado como operador(a) ao registro fiduciário <b>' . $pedido->protocolo_pedido . '</b> da instituição financeira <b>' . $pedido->pessoa_origem->no_pessoa . '</b>.';
                Mail::to($usuario->email_usuario, $usuario->no_usuario)
                    ->queue(new NotificacaoOperadorRegistroFiduciarioMail($registro_fiduciario, $registro_fiduciario_operador, $mensagem, $assunto));
            }
            
            if (count($request->id_usuario)>1) {
                $texto_historico = count($request->id_usuario) . ' operadores foram inseridos';
                $texto_retorno = 'Os operadores do registro foram inseridos com sucesso.';
            } else {
                $texto_historico = '1 operador foi inserido';
                $texto_retorno = 'O operador do registro foi inserido com sucesso.';
            }

            // Insere o histórico do pedido            
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, $texto_historico . ' com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                $texto_retorno,
                'Registro - Operadores',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'O operador foi inserido com sucesso.'
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Error ao salvar o operador do registro.',
                'Registro - Operadores',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
            ];
            return response()->json($response_json, 500);
        }
    }

    /**
     * @param Request $request
     * @return ResponseFactory
     */
    public function destroy(Request $request)
    {
        $registro_fiduciario_operador = $this->RegistroFiduciarioOperadorServiceInterface->buscar($request->operadore);

        if ($registro_fiduciario_operador) {
            DB::beginTransaction();

            try {
                $registro_fiduciario = $registro_fiduciario_operador->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $this->RegistroFiduciarioOperadorServiceInterface->deletar($registro_fiduciario_operador);

                $usuario = $registro_fiduciario_operador->usuario;
                $assunto = 'Você não é mais operador(a) do registro ' . $pedido->protocolo_pedido;
                $mensagem = 'O seu usuário foi removido da lista de operadores do registro fiduciário <b>' . $pedido->protocolo_pedido . '</b> da instituição financeira <b>' . $pedido->pessoa_origem->no_pessoa . '</b>.';
                Mail::to($usuario->email_usuario, $usuario->no_usuario)
                    ->queue(new NotificacaoOperadorRegistroFiduciarioMail($registro_fiduciario, $registro_fiduciario_operador, $mensagem, $assunto));

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O operador '.$registro_fiduciario_operador->usuario->no_usuario.' foi removido com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    5,
                    'O operador do registro foi removido com sucesso.',
                    'Registro - Operadores',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O operador foi removido com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao remover o operador do registro.',
                    'Registro - Operadores',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json, 500);
            }
        }
    }
}
