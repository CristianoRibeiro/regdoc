<?php

namespace App\Http\Controllers\Documentos;

use Gate;
use DB;
use stdClass;
use Helper;
use LogDB;
use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Documentos\UpdateDocumentoContrato;

use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;

class DocumentoContratoController extends Controller
{

    protected $DocumentoServiceInterface;
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;

    public function __construct(DocumentoServiceInterface $DocumentoServiceInterface,
                                EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface) {
        parent::__construct();
        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        if ($documento) {
            $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
            if($documento->id_cidade_foro) {
                $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($documento->cidade_foro->id_estado);
            }

            $compact_args = [
                'documento' => $documento,
                'estados_disponiveis' => $estados_disponiveis,
                'cidades_disponiveis' => $cidades_disponiveis ?? [],
            ];

            return view('app.produtos.documentos.detalhes.contrato.geral-documentos-contrato', $compact_args);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateDocumentoContrato $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentoContrato $request)
    {
        DB::beginTransaction();
        try {
            $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

            if ($documento) {
                // Insere o documento
                $args_documento_contrato = new stdClass();
                $args_documento_contrato->nu_proposta = $request->nu_proposta;
                $args_documento_contrato->nu_contrato = $request->nu_contrato;

                $args_documento_contrato->nu_desagio = Helper::converte_float($request->nu_desagio);
                $args_documento_contrato->tp_forma_pagamento = $request->tp_forma_pagamento;
                switch ($request->tp_forma_pagamento) {
                    case 1:
                        $args_documento_contrato->nu_desagio_dias_apos_vencto = intval($request->nu_desagio_dias_apos_vencto);
                        break;
                    case 2:
                        $args_documento_contrato->nu_dias_primeira_parcela = intval($request->nu_dias_primeira_parcela);
                        $args_documento_contrato->nu_dias_segunda_parcela = intval($request->nu_dias_segunda_parcela);
                        $args_documento_contrato->pc_primeira_parcela = Helper::converte_float($request->pc_primeira_parcela);
                        $args_documento_contrato->pc_segunda_parcela = Helper::converte_float($request->pc_segunda_parcela);
                        break;
                }
                $args_documento_contrato->nu_cobranca_dias_inadimplemento = intval($request->nu_cobranca_dias_inadimplemento);
                $args_documento_contrato->nu_acessor_dias_inadimplemento = intval($request->nu_acessor_dias_inadimplemento);
                $args_documento_contrato->vl_despesas_condominio = Helper::converte_float($request->vl_despesas_condominio);
                $args_documento_contrato->id_cidade_foro = $request->id_cidade_foro;

                $atualizacao_documento_contrato = $this->DocumentoServiceInterface->alterar($documento ,$args_documento_contrato);
            }

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Atualização de Contrato do Documento '.$atualizacao_documento_contrato->uuid.' com sucesso.',
                'Documentos',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O contrato foi atualizado com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao atualizar o contrato',
                'Documentos',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }
}
