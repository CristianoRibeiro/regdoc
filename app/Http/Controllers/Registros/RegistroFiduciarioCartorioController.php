<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use DB;
use Auth;
use LogDB;
use stdClass;
use Carbon\Carbon;
use Gate;

use App\Http\Requests\RegistroFiduciario\Completar\UpdateRegistroFiduciarioCartorio;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;

class RegistroFiduciarioCartorioController extends Controller
{
    /**
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var PedidoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var IntegracaoRegistroFiduciarioServiceInterface
     * @var PessoaServiceInterface;
     */
    private $EstadoServiceInterface;
    private $CidadeServiceInterface;
    private $PedidoServiceInterface;
    private $HistoricoPedidoServiceInterface;
    private $RegistroFiduciarioServiceInterface;
    private $IntegracaoRegistroFiduciarioServiceInterface;
    private $PessoaServiceInterface;

    /**
     * RegistroFiduciarioCartorioController constructor.
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param PedidoServiceInterface $PedidoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface
     * @param PessoaServiceInterface $PessoaServiceInterface
     */
    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                PedidoServiceInterface $PedidoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface,
                                PessoaServiceInterface $PessoaServiceInterface)
    {
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->IntegracaoRegistroFiduciarioServiceInterface = $IntegracaoRegistroFiduciarioServiceInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-cartorio', $registro_fiduciario);

        switch ($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto) {
            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                $id_estado = $registro_fiduciario->serventia_ri->pessoa->enderecos[0]->cidade->id_estado ?? NULL;
                $id_cidade = $registro_fiduciario->serventia_ri->pessoa->enderecos[0]->id_cidade ?? NULL;
                $id_pessoa_cartorio_ri = $registro_fiduciario->serventia_ri->id_pessoa ?? NULL;

                $tipos_serventia = [1, 10];

                $view = "app.produtos.registro-fiduciario.detalhes.completar.geral-registro-cartorio-fiduciario";
                break;
            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                $id_estado = $registro_fiduciario->serventia_nota->pessoa->enderecos[0]->cidade->id_estado ?? NULL;
                $id_cidade = $registro_fiduciario->serventia_nota->pessoa->enderecos[0]->id_cidade ?? NULL;
                $id_pessoa_cartorio_rtd = $registro_fiduciario->serventia_nota->id_pessoa ?? NULL;

                $tipos_serventia = [2, 3, 10];

                $view = "app.produtos.registro-fiduciario.detalhes.completar.geral-registro-cartorio-garantia";
                break;
        }

        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        if ($id_estado) {
            $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($id_estado);

            if ($id_cidade) {
                $pessoas_cartorio_disponiveis = $this->PessoaServiceInterface->pessoa_disponiveis([2], $tipos_serventia, $id_cidade);
            }
        }

        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'pessoas_cartorio_disponiveis' => $pessoas_cartorio_disponiveis ?? [],

            'id_estado' => $id_estado,
            'id_cidade' => $id_cidade,
            'id_pessoa_cartorio_ri' => $id_pessoa_cartorio_ri ?? NULL,
            'id_pessoa_cartorio_rtd' => $id_pessoa_cartorio_rtd ?? NULL
        ];

        return view($view, $compact_args);
    }

    /**
     * @param UpdateRegistroFiduciarioCartorio $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateRegistroFiduciarioCartorio $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-cartorio', $registro_fiduciario);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        DB::beginTransaction();

        try {
            switch ($pedido->id_produto) {
                case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                    // Buscar pessoa do cartório de imóveis
                    $pessoa_serventia = $this->PessoaServiceInterface->buscar($request->id_pessoa_cartorio_ri);
                    if (!$pessoa_serventia)
                        throw new Exception('Não foi possível encontrar o cartório de registro de imóveis informado.');

                    $id_serventia_ri = $pessoa_serventia->serventia->id_serventia;
                    break;
                case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                    // Buscar pessoa do cartório de notas
                    $pessoa_serventia = $this->PessoaServiceInterface->buscar($request->id_pessoa_cartorio_rtd);
                    if (!$pessoa_serventia)
                        throw new Exception('Não foi possível encontrar o cartório de registro de imóveis informado.');

                    $id_serventia_notas = $pessoa_serventia->serventia->id_serventia;
                    break;
            }

            if ($request->in_atualizar_integracao=='S') {
                $args_integracao_registro_fiduciario = new stdClass();
                $args_integracao_registro_fiduciario->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
                $args_integracao_registro_fiduciario->id_grupo_serventia = $pessoa_serventia->serventia->id_grupo_serventia ?? NULL;
                $args_integracao_registro_fiduciario->id_serventia = $pessoa_serventia->serventia->id_serventia ?? NULL;
                $args_integracao_registro_fiduciario->id_pessoa = $pedido->id_pessoa_origem;

                $id_integracao = $this->IntegracaoRegistroFiduciarioServiceInterface->definir_integracao($args_integracao_registro_fiduciario);

                $historico = 'O cartório e integração foram atualizados com sucesso.';
            } else {
                $historico = 'O cartório foi atualizado com sucesso.';
            }

            $args_alterar_registro = new stdClass();
            $args_alterar_registro->id_serventia_ri = $id_serventia_ri ?? NULL;
            $args_alterar_registro->id_serventia_nota = $id_serventia_notas ?? NULL;
            $args_alterar_registro->id_integracao = $id_integracao ?? NULL;
            $args_alterar_registro->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_alterar_registro);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, $historico); 

            if ($pedido->id_situacao_pedido_grupo_produto == config('constants.SITUACAO.11.ID_DEFINIR_CARTORIO')) {
                $args_alterar_pedido = new stdClass();
                $args_alterar_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO');

                $this->PedidoServiceInterface->alterar($pedido, $args_alterar_pedido);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A situação do registro foi alterada para contrato cadastrado.');
            }

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                $historico,
                'Registro - Cartório',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => $historico
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro na atualização do contrato do registro',
                'Registro - Cartório',
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
