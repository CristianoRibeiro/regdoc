<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;

use App\Http\Requests\RegistroFiduciario\Procurador\UpdateRegistroFiduciarioParteProcurador;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_procurador;


use DB;
use Helper;
use Gate;
use stdClass;
use LogDB;
use Auth;


class RegistroFiduciarioProcuradorController extends Controller
{
    /**
     * @var RegistroFiduciarioProcuradorServiceInterface
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var HistoricoPedidoServiceInterface
     * 
    **/

    protected $RegistroFiduciarioProcuradorServiceInterface;
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $HistoricoPedidoServiceInterface;

    /**
     * RegistroFiduciarioProcuradorController constructor.
     * @param RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
    **/

    public function __construct(RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface,
                                EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioProcuradorServiceInterface = $RegistroFiduciarioProcuradorServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
    }

    public function detalhes_editar(Request $request)
    {
 
         $registro_finduciario_procurador = $this->RegistroFiduciarioProcuradorServiceInterface->buscar_procurador($request->id_procurador);

         if($registro_finduciario_procurador){

            if($registro_finduciario_procurador->id_cidade>0){
                $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($registro_finduciario_procurador->cidade->id_estado);
            }
            $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

            // Argumentos para o retorno da view
            $compact_args = [
                'estados_disponiveis' => $estados_disponiveis,
                'cidades_disponiveis' => $cidades_disponiveis ?? [],
                'registro_finduciario_procurador' => $registro_finduciario_procurador
            ];

            switch ($request->operacao) {
                case 'detalhes':
                    return view('app.produtos.registro-fiduciario.procurador.geral-registro-detalhes-procurador', $compact_args);
                    break;

                case 'editar':
                    return view('app.produtos.registro-fiduciario.procurador.geral-registro-editar-procurador', $compact_args);
                    break;    
                        
                default:
                    # code...
                    break;
            }

        }

    }


    public function salvar_atualizar(UpdateRegistroFiduciarioParteProcurador $request)
    {
        
        $registro_finduciario_procurador = $this->RegistroFiduciarioProcuradorServiceInterface->buscar_procurador($request->id_registro_finduciario_procurador);

        $registro_fiduciario_parte = $registro_finduciario_procurador->registro_fiduciario_parte;

        Gate::authorize('registros-detalhes-partes-editar', $registro_fiduciario_parte);

        if ($registro_finduciario_procurador) {
            
            DB::beginTransaction();

            try {

                $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $telefone_parte = Helper::array_telefone($request->nu_telefone_contato);

                $args_atualizar_procurado = new stdClass();
                $args_atualizar_procurado->no_procurador = $request->no_procurador;
                $args_atualizar_procurado->nu_cpf_cnpj = Helper::somente_numeros($request->nu_cpf_cnpj);
                $args_atualizar_procurado->nu_cep = Helper::somente_numeros($request->nu_cep) ?? NULL;
                $args_atualizar_procurado->no_endereco = $request->no_endereco ?? NULL;
                $args_atualizar_procurado->nu_endereco = $request->nu_endereco ?? NULL;
                $args_atualizar_procurado->no_bairro = $request->no_bairro ?? NULL;
                $args_atualizar_procurado->id_cidade = $request->id_cidade ?? NULL;
                $args_atualizar_procurado->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                $args_atualizar_procurado->no_email_contato = $request->no_email_contato ?? NULL;
                $args_atualizar_procurado->in_emitir_certificado = $request->in_emitir_certificado ?? 'N';
                $args_atualizar_procurado->in_cnh = $request->in_cnh ?? 'N';

                $procurador = $this->RegistroFiduciarioProcuradorServiceInterface->alterar($registro_finduciario_procurador, $args_atualizar_procurado);

                DB::commit();

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O procurador '.$procurador->no_procurador.' foi alterado com sucesso.');

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'Procurador foi alterado com sucesso.',
                    'Registro - Parte procurador',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O procurador foi alterado com sucesso.',
                ];
                return response()->json($response_json);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao alterar o procurador da parte.',
                    'Registro - Partes',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                    'regarrega' => 'false'
                ];
                return response()->json($response_json, 500);
            }
 
        }
          
    }
}