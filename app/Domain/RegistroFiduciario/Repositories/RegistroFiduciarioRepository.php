<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;
use Helper;
use Exception;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class RegistroFiduciarioRepository implements RegistroFiduciarioRepositoryInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        $registro_fiduciarios = new registro_fiduciario();
        $registro_fiduciarios = $registro_fiduciarios->select('registro_fiduciario.*')
            ->join('registro_fiduciario_pedido', 'registro_fiduciario_pedido.id_registro_fiduciario', '=', 'registro_fiduciario.id_registro_fiduciario')
            ->join('pedido', 'pedido.id_pedido', '=', 'registro_fiduciario_pedido.id_pedido')
            ->leftJoin('pedido_central', 'pedido_central.id_pedido', '=', 'pedido.id_pedido');

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 8:
                $registro_fiduciarios = $registro_fiduciarios->join('pedido_pessoa', function ($join) {
                    $join->on('pedido_pessoa.id_pedido', '=', 'pedido.id_pedido')
                    ->where('pedido_pessoa.id_pessoa', Auth::User()->pessoa_ativa->id_pessoa);
                });
                break;
        }

        if (($filtros->id_produto ?? 0) > 0) {
            $registro_fiduciarios = $registro_fiduciarios->where('pedido.id_produto', $filtros->id_produto);
        }
        if (isset($filtros->protocolo)) {
            $registro_fiduciarios = $registro_fiduciarios->where('pedido.protocolo_pedido', 'like', '%' . $filtros->protocolo . '%');
        }
        if (isset($filtros->data_cadastro_ini) && isset($filtros->data_cadastro_fim)) {
            $data_importacao_ini = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_ini)->startOfDay();
            $data_importacao_fim = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_fim)->endOfDay();
            $registro_fiduciarios = $registro_fiduciarios->whereBetween('registro_fiduciario.dt_cadastro', [$data_importacao_ini, $data_importacao_fim]);
        }
        if (isset($filtros->cpfcnpj_parte) || isset($filtros->nome_parte)) {
            $registro_fiduciarios = $registro_fiduciarios->join('registro_fiduciario_parte', 'registro_fiduciario.id_registro_fiduciario', '=', 'registro_fiduciario_parte.id_registro_fiduciario')
                ->leftJoin('registro_fiduciario_procurador', 'registro_fiduciario_parte.id_registro_fiduciario_parte', '=', 'registro_fiduciario_procurador.id_registro_fiduciario_parte');

            if ($filtros->cpfcnpj_parte) {
                $cpf_cnpj = Helper::somente_numeros($filtros->cpfcnpj_parte);
                $registro_fiduciarios = $registro_fiduciarios->where(function($where) use ($cpf_cnpj) {
                    $where
                        ->where('registro_fiduciario_parte.nu_cpf_cnpj', '=', $cpf_cnpj)
                        ->orWhere('registro_fiduciario_procurador.nu_cpf_cnpj', '=', $cpf_cnpj);
                });
            }
            if ($filtros->nome_parte) {
                $nome = $filtros->nome_parte;
                $registro_fiduciarios = $registro_fiduciarios->where(function($where) use ($nome) {
                    $where
                        ->whereRaw("unaccent(registro_fiduciario_parte.no_parte) ilike unaccent(?)",  "%{$nome}%")
                        ->orWhereRaw("unaccent(registro_fiduciario_procurador.no_procurador) ilike unaccent(?)", "%{$nome}%");
                });
            }
        }
        if (isset($filtros->id_estado_cartorio)) {
            $registro_fiduciarios = $registro_fiduciarios
                ->join('serventia', 'serventia.id_serventia', '=', 'registro_fiduciario.id_serventia_ri');
            
            if(isset($filtros->id_pessoa_cartorio)) { // dentro do filtro de estado pois na view, para aparecer o cartorio, precisa selecionar cidade e estado.
                $registro_fiduciarios = $registro_fiduciarios->where('serventia.id_pessoa', $filtros->id_pessoa_cartorio);
            } else {
                $registro_fiduciarios
                    ->join('pessoa', 'serventia.id_pessoa', '=', 'pessoa.id_pessoa')
                    ->join('pessoa_endereco', 'serventia.id_pessoa', '=', 'pessoa_endereco.id_pessoa')
                    ->join('endereco', 'pessoa_endereco.id_endereco', '=', 'endereco.id_endereco')
                    ->join('cidade', 'endereco.id_cidade', '=', 'cidade.id_cidade')
                    ->where('cidade.id_estado', $filtros->id_estado_cartorio);

                if (isset($filtros->id_cidade_cartorio)) {
                    $registro_fiduciarios = $registro_fiduciarios->where('cidade.id_cidade', $filtros->id_cidade_cartorio);
                }
            }
        }

        if (isset($filtros->id_registro_fiduciario_tipo)) {
            $registro_fiduciarios = $registro_fiduciarios->where('registro_fiduciario.id_registro_fiduciario_tipo', '=', $filtros->id_registro_fiduciario_tipo);
        }

        if (isset($filtros->id_situacao_pedido_grupo_produto)) {
            $registro_fiduciarios = $registro_fiduciarios->whereIn('pedido.id_situacao_pedido_grupo_produto', $filtros->id_situacao_pedido_grupo_produto);
        }
        if (isset($filtros->nu_contrato)) {
            $registro_fiduciarios = $registro_fiduciarios->where('registro_fiduciario.nu_contrato', 'ilike', '%'.$filtros->nu_contrato.'%');
        }
        if (isset($filtros->nu_proposta)) {
            $registro_fiduciarios = $registro_fiduciarios->where('registro_fiduciario.nu_proposta', 'ilike', '%'.$filtros->nu_proposta.'%');
        }
        if (isset($filtros->nu_unidade_empreendimento)) {
            $registro_fiduciarios = $registro_fiduciarios->whereRaw("unaccent(nu_unidade_empreendimento) ilike unaccent(?)",  "%{$filtros->nu_unidade_empreendimento}%");
        }
        if (isset($filtros->id_pessoa_origem)) {
            $registro_fiduciarios = $registro_fiduciarios->where('pedido.id_pessoa_origem', '=', $filtros->id_pessoa_origem);
        }
        
        if (isset($filtros->id_usuario_cad)) {
            $registro_fiduciarios = $registro_fiduciarios->where('pedido.id_usuario', '=', $filtros->id_usuario_cad);
        }
        if (isset($filtros->nu_protocolo_central)) {
            $registro_fiduciarios = $registro_fiduciarios
                ->leftJoin('arisp_pedido', 'arisp_pedido.id_pedido', '=', 'pedido.id_pedido')
                ->where(function($where) use ($filtros) {
                    $where->where('pedido_central.nu_protocolo_central', '=', $filtros->nu_protocolo_central)
                        ->orWhere('arisp_pedido.pedido_protocolo', '=', $filtros->nu_protocolo_central);
                });
        }
        if (isset($filtros->id_usuario_operador)) {
            if ($filtros->id_usuario_operador>0) {
                $registro_fiduciarios = $registro_fiduciarios->join('registro_fiduciario_operador', function($join) use ($filtros) {
                    $join->on('registro_fiduciario_operador.id_registro_fiduciario', '=', 'registro_fiduciario.id_registro_fiduciario')
                        ->where('registro_fiduciario_operador.in_registro_ativo', '=', 'S')
                        ->where('registro_fiduciario_operador.id_usuario', '=', $filtros->id_usuario_operador);
                });
            } else {
                $registro_fiduciarios = $registro_fiduciarios->leftJoin('registro_fiduciario_operador', function($join) {
                        $join->on('registro_fiduciario_operador.id_registro_fiduciario', 'registro_fiduciario.id_registro_fiduciario')
                            ->where('registro_fiduciario_operador.in_registro_ativo', 'S');
                    })
                    ->whereNull('registro_fiduciario_operador.id_registro_fiduciario_operador');
            }
        }

        if (($filtros->nu_prenotacao ?? 0) > 0) {
            $registro_fiduciarios = $registro_fiduciarios
                ->where('pedido_central.nu_protocolo_prenotacao', 'ilike', '%' . $filtros->nu_prenotacao . '%');
        }
        if (isset($filtros->ids_integracao)) {
            $registro_fiduciarios = $registro_fiduciarios->whereIn('registro_fiduciario.id_integracao', $filtros->ids_integracao);
        }

        // Finalização do histórico
        $registro_fiduciarios = $registro_fiduciarios->groupBy('registro_fiduciario.id_registro_fiduciario')
            ->orderBy('registro_fiduciario.dt_cadastro','desc');

        return $registro_fiduciarios;
    }

    /**
     * @param int $id_registro_fiduciario
     * @return registro_fiduciario|null
     */
    public function buscar(int $id_registro_fiduciario) : ?registro_fiduciario
    {
        return registro_fiduciario::findOrFail($id_registro_fiduciario);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario
    {
        return registro_fiduciario::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario
    {
        $novo_registro = new registro_fiduciario();
        $novo_registro->uuid = Uuid::uuid4();
        $novo_registro->nu_contrato = $args->nu_contrato ?? NULL;
        $novo_registro->nu_proposta = $args->nu_proposta ?? NULL;
        $novo_registro->in_importado = $args->in_importado ?? 'N';
        $novo_registro->in_api = $args->in_api ?? 'N';
        $novo_registro->id_usuario_cad = Auth::User()->id_usuario;
        $novo_registro->id_serventia_ri = $args->id_serventia_ri;
        $novo_registro->id_serventia_nota = $args->id_serventia_nota;
        $novo_registro->id_registro_fiduciario_tipo = $args->id_registro_fiduciario_tipo ?? NULL;
        $novo_registro->id_registro_fiduciario_apresentante = $args->id_registro_fiduciario_apresentante ?? NULL;
        $novo_registro->id_empreendimento = $args->id_empreendimento ?? NULL;
        $novo_registro->no_empreendimento = $args->no_empreendimento ?? NULL;
        $novo_registro->nu_unidade_empreendimento = $args->nu_unidade_empreendimento ?? NULL;
        $novo_registro->id_integracao = $args->id_integracao ?? NULL;
        $novo_registro->in_contrato_completado = $args->in_contrato_completado ?? 'N';
        $novo_registro->in_operacao_completada = $args->in_operacao_completada ?? 'N';
        $novo_registro->in_financiamento_completado = $args->in_financiamento_completado ?? 'N';
        $novo_registro->in_cedula_completada = $args->in_cedula_completada ?? 'N';
        $novo_registro->id_integracao = $args->id_integracao ?? NULL;
        $novo_registro->in_contrato_assinado = $args->in_contrato_assinado ?? 'N';
        $novo_registro->in_instrumento_assinado = $args->in_instrumento_assinado ?? 'N';
        $novo_registro->dt_prenotacao = $args->dt_prenotacao ?? NULL;
        $novo_registro->dt_vencto_prenotacao = $args->dt_vencto_prenotacao ?? NULL;
        $novo_registro->id_registro_fiduciario_custodiante = $args->id_registro_fiduciario_custodiante ?? NULL;
        $novo_registro->dt_cadastro_contrato = $args->dt_cadastro_contrato ?? NULL;
        if (!$novo_registro->save()) {
            throw new Exception('Erro ao salvar o registro fiduciário.');
        }

        return $novo_registro;
    }

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @param stdClass $args
     * @return registro_fiduciario
     * @throws Exception
     */
    public function alterar($registro_fiduciario, stdClass $args) : registro_fiduciario
    {
        if(isset($args->modelo_contrato)) {
            $registro_fiduciario->modelo_contrato = $args->modelo_contrato;
        }
        if(isset($args->nu_contrato)) {
            $registro_fiduciario->nu_contrato = $args->nu_contrato;
        }
        if(isset($args->id_cidade_emissao_contrato)) {
            $registro_fiduciario->id_cidade_emissao_contrato = $args->id_cidade_emissao_contrato;
        }
        if(isset($args->dt_emissao_contrato)) {
            $registro_fiduciario->dt_emissao_contrato = $args->dt_emissao_contrato;
        }
        if(isset($args->id_serventia_ri)) {
            $registro_fiduciario->id_serventia_ri = $args->id_serventia_ri;
        }
        if(isset($args->id_serventia_nota)) {
            $registro_fiduciario->id_serventia_nota = $args->id_serventia_nota;
        }
        if(isset($args->id_registro_fiduciario_natureza)) {
            $registro_fiduciario->id_registro_fiduciario_natureza = $args->id_registro_fiduciario_natureza;
        }
        if(isset($args->id_registro_fiduciario_cedula)) {
            $registro_fiduciario->id_registro_fiduciario_cedula = $args->id_registro_fiduciario_cedula;
        }
        if(isset($args->id_registro_fiduciario_tipo)) {
            $registro_fiduciario->id_registro_fiduciario_tipo = $args->id_registro_fiduciario_tipo;
        }
        if(isset($args->id_registro_fiduciario_apresentante)) {
            $registro_fiduciario->id_registro_fiduciario_apresentante = $args->id_registro_fiduciario_apresentante;
        }
        if(isset($args->id_empreendimento)) {
            $registro_fiduciario->id_empreendimento = $args->id_empreendimento;
        }
        if(isset($args->no_empreendimento)) {
            $registro_fiduciario->no_empreendimento = $args->no_empreendimento;
        }
        if(isset($args->nu_unidade_empreendimento)) {
            $registro_fiduciario->nu_unidade_empreendimento = $args->nu_unidade_empreendimento;
        }
        if(isset($args->id_integracao)) {
            $registro_fiduciario->id_integracao = $args->id_integracao;
        }
        if (isset($args->dt_assinatura_contrato)) {
            $registro_fiduciario->dt_assinatura_contrato = $args->dt_assinatura_contrato;
        }
        if (isset($args->dt_entrada_registro)) {
            $registro_fiduciario->dt_entrada_registro = $args->dt_entrada_registro;
        }
        if (isset($args->dt_registro)) {
            $registro_fiduciario->dt_registro = $args->dt_registro;
        }
        if (isset($args->dt_alteracao)) {
            $registro_fiduciario->dt_alteracao = $args->dt_alteracao;
        }
        if(isset($args->in_contrato_completado)) {
            $registro_fiduciario->in_contrato_completado = $args->in_contrato_completado;
        }
        if(isset($args->in_operacao_completada)) {
            $registro_fiduciario->in_operacao_completada = $args->in_operacao_completada;
        }
        if(isset($args->in_financiamento_completado)) {
            $registro_fiduciario->in_financiamento_completado = $args->in_financiamento_completado;
        }
        if(isset($args->in_cedula_completada)) {
            $registro_fiduciario->in_cedula_completada = $args->in_cedula_completada;
        }
        if(isset($args->id_integracao)) {
            $registro_fiduciario->id_integracao = $args->id_integracao;
        }
        if(isset($args->in_contrato_assinado)) {
            $registro_fiduciario->in_contrato_assinado = $args->in_contrato_assinado;
        }
        if(isset($args->in_instrumento_assinado)) {
            $registro_fiduciario->in_instrumento_assinado = $args->in_instrumento_assinado;
        }
        if(isset($args->dt_prenotacao)) {
            $registro_fiduciario->dt_prenotacao = $args->dt_prenotacao;
        }
        if(isset($args->dt_vencto_prenotacao)) {
            $registro_fiduciario->dt_vencto_prenotacao = $args->dt_vencto_prenotacao;
        }
        if(isset($args->id_registro_fiduciario_custodiante)) {
            $registro_fiduciario->id_registro_fiduciario_custodiante = $args->id_registro_fiduciario_custodiante;
        }
        if(isset($args->dt_cadastro_contrato)) {
            $registro_fiduciario->dt_cadastro_contrato = $args->dt_cadastro_contrato;
        }
        if(isset($args->dt_finalizacao)) {
            $registro_fiduciario->dt_finalizacao = $args->dt_finalizacao;
        }
        if (isset($args->in_pago_itbi)) {
            $registro_fiduciario->in_pago_itbi = $args->in_pago_itbi;
        }
        if (!$registro_fiduciario->save()) {
            throw new Exception('Erro ao atualizar o contrato do registro.');
        }

        $registro_fiduciario->refresh();

        return $registro_fiduciario;
    }
}
