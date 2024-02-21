<?php

namespace App\Domain\Documento\Documento\Repositories;

use Exception;
use stdClass;
use Auth;
use Helper;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\Documento\Documento\Models\documento;

use App\Domain\Documento\Documento\Contracts\DocumentoRepositoryInterface;

class DocumentoRepository implements DocumentoRepositoryInterface
{
    public function listar(stdClass $filtros) : \Illuminate\Pagination\LengthAwarePaginator
    {
        $documentos = documento::select('documento.*')
            ->join('pedido', 'pedido.id_pedido', '=', 'documento.id_pedido');

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 8:
                $documentos = $documentos->join('pedido_pessoa', function ($join) {
                    $join->on('pedido_pessoa.id_pedido', '=', 'pedido.id_pedido')
                         ->where('pedido_pessoa.id_pessoa', Auth::User()->pessoa_ativa->id_pessoa);
                });
                break;
        }

        if ($filtros->protocolo) {
            $documentos = $documentos->where('pedido.protocolo_pedido', 'like', '%' . $filtros->protocolo . '%');
        }

        if ($filtros->data_cadastro_ini and $filtros->data_cadastro_fim) {
            $data_cadastro_ini = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_ini)->startOfDay();
            $data_cadastro_fim = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_fim)->endOfDay();

            $documentos = $documentos->whereBetween('documento.dt_cadastro', [$data_cadastro_ini, $data_cadastro_fim]);
        }

        if ($filtros->cpfcnpj_parte || $filtros->nome_parte) {
            $documentos = $documentos->join('documento_parte', 'documento_parte.id_documento', '=', 'documento.id_documento')
                ->leftJoin('documento_procurador', 'documento_procurador.id_documento_parte', '=', 'documento_parte.id_documento_parte');  
            if ($filtros->cpfcnpj_parte) {
                $cpf_cnpj = Helper::somente_numeros($filtros->cpfcnpj_parte);
                $documentos = $documentos->where(function($where) use ($cpf_cnpj) {
                    $where->where('documento_parte.nu_cpf_cnpj', '=', $cpf_cnpj)
                        ->orWhere('documento_procurador.nu_cpf_cnpj', '=', $cpf_cnpj);
                });
            }
            if ($filtros->nome_parte) {
                $nome = $filtros->nome_parte;
                $documentos = $documentos->where(function($where) use ($nome) {
                    $where->where('documento_parte.no_parte', 'ilike',  '%'.$nome.'%')
                        ->orWhere('documento_procurador.no_procurador', 'ilike', '%'.$nome.'%');
                });
            }
        }

        if ($filtros->id_situacao_pedido_grupo_produto) {
            $documentos = $documentos->whereIn('pedido.id_situacao_pedido_grupo_produto', $filtros->id_situacao_pedido_grupo_produto);
        }
        if ($filtros->id_pessoa_origem) {
            $documentos = $documentos->where('pedido.id_pessoa_origem', '=', $filtros->id_pessoa_origem);
        }
        if ($filtros->id_usuario_cad) {
            $documentos = $documentos->where('pedido.id_usuario', '=', $filtros->id_usuario_cad);
        }

        $documentos = $documentos->groupBy('documento.id_documento')
            ->orderBy('documento.dt_cadastro', 'DESC')
            ->paginate(10, ['*'], 'pag');

        return $documentos;
    }

    /**
     * @param int $id_documento
     * @return documento|null
     */
    public function buscar(int $id_documento) : ?documento
    {
        return documento::find($id_documento);
    }

    /**
     * @param string $uuid
     * @return documento|null
     */
    public function buscar_uuid(string $uuid) : ?documento
    {
        return documento::where('uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return documento
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento
    {
        $novo_documento = new documento();
        $novo_documento->uuid = Uuid::uuid4();
        $novo_documento->id_documento_tipo  = $args->id_documento_tipo;
        $novo_documento->id_pedido = $args->id_pedido;
        $novo_documento->in_importado = $args->in_importado ?? 'N';
        $novo_documento->in_api = $args->in_api ?? 'N';
        $novo_documento->nu_proposta = $args->nu_proposta ?? NULL;
        $novo_documento->no_titulo = $args->no_titulo ?? NULL;
        $novo_documento->nu_contrato = $args->nu_contrato ?? NULL;
        $novo_documento->nu_desagio = $args->nu_desagio ?? NULL;
        $novo_documento->nu_desagio_dias_apos_vencto = $args->nu_desagio_dias_apos_vencto ?? NULL;
        $novo_documento->nu_desagio_dias_antecedencia_relatorio = $args->nu_desagio_dias_antecedencia_relatorio ?? NULL;
        $novo_documento->nu_cobranca_dias_inadimplemento = $args->nu_cobranca_dias_inadimplemento ?? NULL;
        $novo_documento->nu_acessor_dias_inadimplemento = $args->nu_acessor_dias_inadimplemento ?? NULL;
        $novo_documento->tp_despesas_condominio = $args->tp_despesas_condominio ?? NULL;
        $novo_documento->vl_despesas_condominio = $args->vl_despesas_condominio ?? NULL;
        $novo_documento->id_cidade_foro = $args->id_cidade_foro ?? NULL;
        $novo_documento->tp_forma_pagamento = $args->tp_forma_pagamento ?? NULL;
        $novo_documento->nu_dias_primeira_parcela = $args->nu_dias_primeira_parcela ?? NULL;
        $novo_documento->nu_dias_segunda_parcela = $args->nu_dias_segunda_parcela ?? NULL;
        $novo_documento->id_usuario_cad = Auth::User()->id_usuario;
        $novo_documento->dt_alteracao = $args->dt_alteracao ?? NULL;
        $novo_documento->dt_inicio_proposta = $args->dt_inicio_proposta ?? NULL;
        $novo_documento->dt_transformacao_contrato = $args->dt_transformacao_contrato ?? NULL;
        $novo_documento->dt_assinatura = $args->dt_assinatura ?? NULL;
        $novo_documento->dt_finalizacao = $args->dt_finalizacao ?? NULL;
        $novo_documento->dt_inicio_assinatura = $args->dt_inicio_assinatura ?? NULL;
        $novo_documento->dt_documentos_gerados = $args->dt_documentos_gerados ?? NULL;
        $novo_documento->pc_primeira_parcela = $args->pc_primeira_parcela ?? NULL;
        $novo_documento->pc_segunda_parcela = $args->pc_segunda_parcela ?? NULL;

        if (!$novo_documento->save()) {
            throw new Exception('Erro ao salvar o documento.');
        }

        return $novo_documento;
    }

    /**
     * @param documento $documento
     * @param stdClass $args
     * @return documento
     * @throws Exception
     */
    public function alterar(documento $documento, stdClass $args) : documento
    {
        if (isset($args->id_documento_tipo)) {
            $documento->id_documento_tipo = $args->id_documento_tipo;
        }
        if (isset($args->id_pedido)) {
            $documento->id_pedido = $args->id_pedido;
        }
        if (isset($args->nu_contrato)) {
            $documento->nu_contrato = $args->nu_contrato;
        }
        if (isset($args->nu_desagio)) {
            $documento->nu_desagio = $args->nu_desagio;
        }
        if (isset($args->nu_desagio_dias_apos_vencto)) {
            $documento->nu_desagio_dias_apos_vencto = $args->nu_desagio_dias_apos_vencto;
        }
        if (isset($args->nu_desagio_dias_antecedencia_relatorio)) {
            $documento->nu_desagio_dias_antecedencia_relatorio = $args->nu_desagio_dias_antecedencia_relatorio;
        }
        if (isset($args->nu_cobranca_dias_inadimplemento)) {
            $documento->nu_cobranca_dias_inadimplemento = $args->nu_cobranca_dias_inadimplemento;
        }
        if (isset($args->nu_acessor_dias_inadimplemento)) {
            $documento->nu_acessor_dias_inadimplemento = $args->nu_acessor_dias_inadimplemento;
        }
        if (isset($args->tp_despesas_condominio)) {
            $documento->tp_despesas_condominio = $args->tp_despesas_condominio;
        }
        if (isset($args->vl_despesas_condominio)) {
            $documento->vl_despesas_condominio = $args->vl_despesas_condominio;
        }
        if (isset($args->id_cidade_foro)) {
            $documento->id_cidade_foro = $args->id_cidade_foro;
        }
        if (isset($args->tp_forma_pagamento)) {
            $documento->tp_forma_pagamento = $args->tp_forma_pagamento;
        }
        if (isset($args->nu_dias_primeira_parcela)) {
            $documento->nu_dias_primeira_parcela = $args->nu_dias_primeira_parcela;
        }
        if (isset($args->nu_dias_segunda_parcela)) {
            $documento->nu_dias_segunda_parcela = $args->nu_dias_segunda_parcela;
        }
        if (isset($args->dt_alteracao)) {
            $documento->dt_alteracao = $args->dt_alteracao;
        }
        if (isset($args->dt_inicio_proposta)) {
            $documento->dt_inicio_proposta = $args->dt_inicio_proposta;
        }
        if (isset($args->dt_transformacao_contrato)) {
            $documento->dt_transformacao_contrato = $args->dt_transformacao_contrato;
        }
        if (isset($args->dt_assinatura)) {
            $documento->dt_assinatura = $args->dt_assinatura;
        }
        if (isset($args->dt_finalizacao)) {
            $documento->dt_finalizacao = $args->dt_finalizacao;
        }
        if (isset($args->dt_inicio_assinatura)) {
            $documento->dt_inicio_assinatura = $args->dt_inicio_assinatura;
        }
        if (isset($args->dt_documentos_gerados)) {
            $documento->dt_documentos_gerados = $args->dt_documentos_gerados;
        }
        if (isset($args->pc_primeira_parcela)) {
            $documento->pc_primeira_parcela = $args->pc_primeira_parcela;
        }
        if (isset($args->pc_segunda_parcela)) {
            $documento->pc_segunda_parcela = $args->pc_segunda_parcela;
        }

        if (!$documento->save()) {
            throw new Exception('Erro ao atualizar o documento.');
        }

        $documento->refresh();

        return $documento;
    }


}
