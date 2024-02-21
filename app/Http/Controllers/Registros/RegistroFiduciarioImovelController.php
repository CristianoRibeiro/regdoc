<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Exception;
use DB;
use LogDB;
use Auth;
use stdClass;
use Helper;
use Carbon\Carbon;
use Gate;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLivroServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLocalizacaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelTipoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioEnderecoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;

use App\Http\Requests\RegistroFiduciario\Imoveis\StoreRegistroFiduciarioImovel;
use App\Http\Requests\RegistroFiduciario\Imoveis\UpdateRegistroFiduciarioImovel;

class RegistroFiduciarioImovelController extends Controller
{
    /**
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioImovelLivroServiceInterface
     * @var RegistroFiduciarioImovelLocalizacaoServiceInterface
     * @var RegistroFiduciarioImovelTipoServiceInterface
     * @var RegistroFiduciarioEnderecoServiceInterface
     * @var RegistroFiduciarioImovelServiceInterface
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var HistoricoPedidoServiceInterface
     */
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioImovelLivroServiceInterface;
    protected $RegistroFiduciarioImovelLocalizacaoServiceInterface;
    protected $RegistroFiduciarioImovelTipoServiceInterface;
    protected $RegistroFiduciarioEnderecoServiceInterface;
    protected $RegistroFiduciarioImovelServiceInterface;
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $HistoricoPedidoServiceInterface;

    /**
     * RegistroFiduciarioImovelController constructor.
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioImovelLivroServiceInterface $RegistroFiduciarioImovelLivroServiceInterface
     * @param RegistroFiduciarioImovelLocalizacaoServiceInterface $RegistroFiduciarioImovelLocalizacaoServiceInterface
     * @param RegistroFiduciarioImovelTipoServiceInterface $RegistroFiduciarioImovelTipoServiceInterface
     * @param RegistroFiduciarioEnderecoServiceInterface $RegistroFiduciarioEnderecoServiceInterface
     * @param RegistroFiduciarioImovelServiceInterface $RegistroFiduciarioImovelServiceInterface
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     */
    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioImovelLivroServiceInterface $RegistroFiduciarioImovelLivroServiceInterface,
                                RegistroFiduciarioImovelLocalizacaoServiceInterface $RegistroFiduciarioImovelLocalizacaoServiceInterface,
                                RegistroFiduciarioImovelTipoServiceInterface $RegistroFiduciarioImovelTipoServiceInterface,
                                RegistroFiduciarioEnderecoServiceInterface $RegistroFiduciarioEnderecoServiceInterface,
                                RegistroFiduciarioImovelServiceInterface $RegistroFiduciarioImovelServiceInterface,
                                EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioImovelLivroServiceInterface = $RegistroFiduciarioImovelLivroServiceInterface;
        $this->RegistroFiduciarioImovelLocalizacaoServiceInterface = $RegistroFiduciarioImovelLocalizacaoServiceInterface;
        $this->RegistroFiduciarioImovelTipoServiceInterface = $RegistroFiduciarioImovelTipoServiceInterface;
        $this->RegistroFiduciarioEnderecoServiceInterface = $RegistroFiduciarioEnderecoServiceInterface;
        $this->RegistroFiduciarioImovelServiceInterface = $RegistroFiduciarioImovelServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
    }

    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-imoveis-novo', $registro_fiduciario);

        if ($registro_fiduciario) {
            $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

            $imovel_livros = $this->RegistroFiduciarioImovelLivroServiceInterface->imovel_livros();
            $imovel_localizacoes = $this->RegistroFiduciarioImovelLocalizacaoServiceInterface->imovel_localizacoes();
            $imovel_tipos = $this->RegistroFiduciarioImovelTipoServiceInterface->imovel_tipos();

            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'estados_disponiveis' => $estados_disponiveis,
                'imovel_livros' => $imovel_livros,
                'imovel_localizacoes' => $imovel_localizacoes,
                'imovel_tipos' => $imovel_tipos,
                'cidades_disponiveis' => []
            ];

            return view('app.produtos.registro-fiduciario.detalhes.imovel.geral-registro-imovel', $compact_args);
        }
    }

    /**
     * @param StoreRegistroFiduciarioImovel $request
     */
    public function store(StoreRegistroFiduciarioImovel $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-imoveis-novo', $registro_fiduciario);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $args_endereco = new stdClass();
                $args_endereco->id_registro_fiduciario = $request->id_registro_fiduciario;
                $args_endereco->nu_cep = Helper::somente_numeros($request->nu_cep);
                $args_endereco->no_endereco = $request->no_endereco;
                $args_endereco->nu_endereco = $request->nu_endereco;
                $args_endereco->no_bairro = $request->no_bairro;
                $args_endereco->no_complemento = $request->no_complemento;
                $args_endereco->id_cidade = $request->id_cidade;

                $registro_endereco = $this->RegistroFiduciarioEnderecoServiceInterface->inserir($args_endereco);

                $args_imovel = new stdClass();
                $args_imovel->id_registro_fiduciario = $request->id_registro_fiduciario;
                $args_imovel->id_registro_fiduciario_imovel_tipo = $request->id_registro_fiduciario_imovel_tipo;
                $args_imovel->id_registro_fiduciario_imovel_localizacao = $request->id_registro_fiduciario_imovel_localizacao;
                $args_imovel->id_registro_fiduciario_imovel_livro = $request->id_registro_fiduciario_imovel_livro;
                $args_imovel->id_registro_fiduciario_endereco = $registro_endereco->id_registro_fiduciario_endereco;
                $args_imovel->nu_matricula = $request->nu_matricula;
                $args_imovel->nu_iptu = $request->nu_iptu;
                $args_imovel->nu_ccir = $request->nu_ccir;
                $args_imovel->nu_nirf = $request->nu_nirf;
                $args_imovel->va_compra_venda = Helper::converte_float($request->va_compra_venda);
                $args_imovel->va_venal = Helper::converte_float($request->va_venal);

                $this->RegistroFiduciarioImovelServiceInterface->inserir($args_imovel);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O imóvel '.$request->nu_matricula.' foi inserido com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O imóvel foi inserido com sucesso.',
                    'Registro - Imóveis',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O imóvel foi inserido com sucesso.',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao salvar o imóvel do registro.',
                    'Registro - Imóveis',
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

    /**
     * @param Request $request
     */
    public function show(Request $request)
    {
        if ($request->imovel) {
            $registro_fiduciario_imovel = $this->RegistroFiduciarioImovelServiceInterface->buscar($request->imovel, true);

            if ($registro_fiduciario_imovel) {
                $registro_fiduciario = $registro_fiduciario_imovel->registro_fiduciario;

                $cidade = $this->CidadeServiceInterface->buscar_cidade($registro_fiduciario_imovel->endereco->id_cidade);

                $registro_fiduciario_imovel->endereco->id_estado = $cidade->id_estado;
                $registro_fiduciario_imovel->endereco->cidade = $cidade->no_cidade;

                $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

                $imovel_livros = $this->RegistroFiduciarioImovelLivroServiceInterface->imovel_livros();
                $imovel_localizacoes = $this->RegistroFiduciarioImovelLocalizacaoServiceInterface->imovel_localizacoes();
                $imovel_tipos = $this->RegistroFiduciarioImovelTipoServiceInterface->imovel_tipos();

                $cidades_disponiveis = [];
                if ($registro_fiduciario_imovel->endereco->id_estado > 0) {
                    $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($registro_fiduciario_imovel->endereco->id_estado);
                }

                // Argumentos para o retorno da view
                $compact_args = [
                    'registro_fiduciario' => $registro_fiduciario,
                    'disabled' => 'disabled', // Desativa os campos na view de Imóvel
                    'imovel' => $registro_fiduciario_imovel,
                    'estados_disponiveis' => $estados_disponiveis,
                    'imovel_livros' => $imovel_livros,
                    'imovel_localizacoes' => $imovel_localizacoes,
                    'imovel_tipos' => $imovel_tipos,
                    'cidades_disponiveis' => $cidades_disponiveis,
                ];

                return view('app.produtos.registro-fiduciario.detalhes.imovel.geral-registro-imovel', $compact_args);
            }
        }
    }

    /**
     * @param Request $request
     */
    public function edit(Request $request)
    {
        if ($request->imovel) {
            $registro_fiduciario_imovel = $this->RegistroFiduciarioImovelServiceInterface->buscar($request->imovel, true);
            $registro_fiduciario = $registro_fiduciario_imovel->registro_fiduciario;

            Gate::authorize('registros-detalhes-imoveis-alterar', $registro_fiduciario);

            if ($registro_fiduciario_imovel) {
                $registro_fiduciario = $registro_fiduciario_imovel->registro_fiduciario;

                $cidade = $this->CidadeServiceInterface->buscar_cidade($registro_fiduciario_imovel->endereco->id_cidade);

                $registro_fiduciario_imovel->endereco->id_estado = $cidade->id_estado;
                $registro_fiduciario_imovel->endereco->cidade = $cidade->no_cidade;

                $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

                $imovel_livros = $this->RegistroFiduciarioImovelLivroServiceInterface->imovel_livros();
                $imovel_localizacoes = $this->RegistroFiduciarioImovelLocalizacaoServiceInterface->imovel_localizacoes();
                $imovel_tipos = $this->RegistroFiduciarioImovelTipoServiceInterface->imovel_tipos();

                $cidades_disponiveis = [];
                if ($registro_fiduciario_imovel->endereco->id_estado > 0) {
                    $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($registro_fiduciario_imovel->endereco->id_estado);
                }

                // Argumentos para o retorno da view
                $compact_args = [
                    'registro_fiduciario' => $registro_fiduciario,
                    'disabled' => '', // Desativa os campos na view de Imóvel
                    'imovel' => $registro_fiduciario_imovel,
                    'estados_disponiveis' => $estados_disponiveis,
                    'imovel_livros' => $imovel_livros,
                    'imovel_localizacoes' => $imovel_localizacoes,
                    'imovel_tipos' => $imovel_tipos,
                    'cidades_disponiveis' => $cidades_disponiveis
                ];

                return view('app.produtos.registro-fiduciario.detalhes.imovel.geral-registro-imovel', $compact_args);
            }
        }
    }

    /**
     * @param UpdateRegistroFiduciarioImovel $request
     */
    public function update(UpdateRegistroFiduciarioImovel $request)
    {
        $registro_fiduciario_imovel = $this->RegistroFiduciarioImovelServiceInterface->buscar($request->imovel, true);
        $registro_fiduciario = $registro_fiduciario_imovel->registro_fiduciario;

        Gate::authorize('registros-detalhes-imoveis-alterar', $registro_fiduciario);

        if ($registro_fiduciario_imovel) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $args_atualizar_endereco = new stdClass();
                $args_atualizar_endereco->nu_cep = Helper::somente_numeros($request->nu_cep);
                $args_atualizar_endereco->no_endereco = $request->no_endereco;
                $args_atualizar_endereco->nu_endereco = $request->nu_endereco;
                $args_atualizar_endereco->no_bairro = $request->no_bairro;
                $args_atualizar_endereco->no_complemento = $request->no_complemento;
                $args_atualizar_endereco->id_cidade = $request->id_cidade;

                $registro_endereco = $this->RegistroFiduciarioEnderecoServiceInterface->alterar($registro_fiduciario_imovel->endereco, $args_atualizar_endereco);

                $args_atualizar_imovel = new stdClass();
                $args_atualizar_imovel->id_registro_fiduciario_imovel_tipo = $request->id_registro_fiduciario_imovel_tipo;
                $args_atualizar_imovel->id_registro_fiduciario_imovel_localizacao = $request->id_registro_fiduciario_imovel_localizacao;
                $args_atualizar_imovel->id_registro_fiduciario_imovel_livro = $request->id_registro_fiduciario_imovel_livro;
                $args_atualizar_imovel->nu_matricula = $request->nu_matricula;
                $args_atualizar_imovel->nu_iptu = $request->nu_iptu;
                $args_atualizar_imovel->nu_ccir = $request->nu_ccir;
                $args_atualizar_imovel->nu_nirf = $request->nu_nirf;
                $args_atualizar_imovel->va_compra_venda = Helper::converte_float($request->va_compra_venda);
                $args_atualizar_imovel->va_venal = Helper::converte_float($request->va_venal);

                $this->RegistroFiduciarioImovelServiceInterface->alterar($registro_fiduciario_imovel, $args_atualizar_imovel);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O imóvel '.$request->nu_matricula.' foi atualizado com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    2,
                    'O imóvel foi atualizado com sucesso.',
                    'Registro - Imóveis',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O imóvel foi atualizado com sucesso.',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao atualizar o imóvel.',
                    'Registro - Imóveis',
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

    /**
     * @param Request $request
     * @return ResponseFactory
     */
    public function destroy(Request $request)
    {
        $registro_fiduciario_imovel = $this->RegistroFiduciarioImovelServiceInterface->buscar($request->imovel,true);
        $registro_fiduciario = $registro_fiduciario_imovel->registro_fiduciario;

        Gate::authorize('registros-detalhes-imoveis-remover', $registro_fiduciario);

        if ($registro_fiduciario_imovel) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O imóvel '.$registro_fiduciario_imovel->nu_matricula.'foi removido com sucesso.');

                $this->RegistroFiduciarioImovelServiceInterface->deletar($registro_fiduciario_imovel);
                $this->RegistroFiduciarioEnderecoServiceInterface->deletar($registro_fiduciario_imovel->endereco);

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    5,
                    'O imóvel foi removido com sucesso.',
                    'Registro - Imóveis',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O imóvel foi removido com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao remover o imóvel.',
                    'Registro - Imóveis',
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
