<?php

namespace App\Domain\VScore\Repositories;

use Exception;
use stdClass;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Domain\VScore\Models\vscore_transacao;

use App\Domain\VScore\Contracts\VScoreTransacaoRepositoryInterface;

class VScoreTransacaoRepository implements VScoreTransacaoRepositoryInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        $vscore_transacoes = new vscore_transacao();

        if (isset($filtros->nu_cpf_cnpj)) {
            $cpf_cnpj = Helper::somente_numeros($filtros->nu_cpf_cnpj);
            $vscore_transacoes = $vscore_transacoes->where('nu_cpf_cnpj', $cpf_cnpj);
        }
        if (isset($filtros->data_cadastro_ini) and isset($filtros->data_cadastro_fim)) {
            $data_cadastro_ini = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_ini)->startOfDay();
            $data_cadastro_fim = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_fim)->endOfDay();

            $vscore_transacoes = $vscore_transacoes->whereBetween('dt_cadastro', [$data_cadastro_ini, $data_cadastro_fim]);
        }
        if (isset($filtros->id_vscore_transacao_situacao)) {
            $vscore_transacoes = $vscore_transacoes->where('id_vscore_transacao_situacao', $filtros->id_vscore_transacao_situacao);
        }
        if (isset($filtros->in_biometria_cpf)) {
            switch($filtros->in_biometria_cpf) {
                case 'S':
                    $vscore_transacoes = $vscore_transacoes->where('in_biometria_cpf', true);
                    break;
                case 'N':
                    $vscore_transacoes = $vscore_transacoes->where(function($where) {
                        $where->where('in_biometria_cpf', false)
                            ->orWhereNull('in_biometria_cpf');
                    });
                    break;
            }
        }
        if (($filtros->id_pessoa ?? 0)>0) {
            $vscore_transacoes = $vscore_transacoes->where('id_pessoa_origem', $filtros->id_pessoa);
        }

        return $vscore_transacoes->orderBy('dt_cadastro', 'DESC');
    }

    /**
     * @param int $id_vscore_transacao
     * @return vscore_transacao|null
     */
    public function buscar(int $id_vscore_transacao) : ?vscore_transacao
    {
        return vscore_transacao::find($id_vscore_transacao);
    }

    /**
     * @param string $uuid
     * @return vscore_transacao|null
     */
    public function buscar_uuid(string $uuid) : ?vscore_transacao
    {
        return vscore_transacao::where('uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return vscore_transacao
     * @throws Exception
     */
    public function inserir(stdClass $args) : vscore_transacao
    {
        $vscore_transacao = new vscore_transacao();
        $vscore_transacao->uuid = Str::uuid();
        $vscore_transacao->id_vscore_transacao_situacao = $args->id_vscore_transacao_situacao;
        $vscore_transacao->id_vscore_transacao_lote = $args->id_vscore_transacao_lote ?? NULL;
        $vscore_transacao->co_momento = $args->co_momento ?? NULL;
        $vscore_transacao->nu_transacao_vscore = $args->nu_transacao_vscore ?? NULL;
        $vscore_transacao->nu_transacao_dvalid = $args->nu_transacao_dvalid ?? NULL;
        $vscore_transacao->no_nome = $args->no_nome ?? NULL;
        $vscore_transacao->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $vscore_transacao->dt_nascimento = $args->dt_nascimento ?? NULL;
        $vscore_transacao->no_filiacao1 = $args->no_filiacao1 ?? NULL;
        $vscore_transacao->no_filiacao2 = $args->no_filiacao2 ?? NULL;
        $vscore_transacao->id_pessoa_origem = Auth::User()->pessoa_ativa->id_pessoa ?? NULL;
        $vscore_transacao->in_biometria_cpf = $args->in_biometria_cpf ?? NULL;
        $vscore_transacao->de_enviado_vscore = $args->de_enviado_vscore ?? NULL;
        $vscore_transacao->de_enviado_dvalid = $args->de_enviado_dvalid ?? NULL;
        $vscore_transacao->de_resultado_vscore = $args->de_resultado_vscore ?? NULL;
        $vscore_transacao->de_resultado_dvalid = $args->de_resultado_dvalid ?? NULL;
        $vscore_transacao->id_usuario_cad = Auth::User()->id_usuario ?? 1;
        if (!$vscore_transacao->save()) {
            throw new Exception('Erro ao salvar a Transação do V/Score.');
        }

        return $vscore_transacao;
    }
    
    /**
     * @param vscore_transacao $vscore_transacao
     * @param stdClass $args
     * @return vscore_transacao
     * @throws Exception
     */
    public function alterar(vscore_transacao $vscore_transacao, stdClass $args): vscore_transacao
    {
        if (isset($args->id_vscore_transacao_situacao)) {
            $vscore_transacao->id_vscore_transacao_situacao = $args->id_vscore_transacao_situacao;
        }
        if (isset($args->co_momento)) {
            $vscore_transacao->co_momento = $args->co_momento;
        }
        if (isset($args->nu_transacao_vscore)) {
            $vscore_transacao->nu_transacao_vscore = $args->nu_transacao_vscore;
        }
        if (isset($args->nu_transacao_dvalid)) {
            $vscore_transacao->nu_transacao_dvalid = $args->nu_transacao_dvalid;
        }
        if (isset($args->de_enviado_vscore)) {
            $vscore_transacao->de_enviado_vscore = $args->de_enviado_vscore;
        }
        if (isset($args->de_enviado_dvalid)) {
            $vscore_transacao->de_enviado_dvalid = $args->de_enviado_dvalid;
        }
        if (isset($args->de_resultado_vscore)) {
            $vscore_transacao->de_resultado_vscore = $args->de_resultado_vscore;
        }
        if (isset($args->de_resultado_dvalid)) {
            $vscore_transacao->de_resultado_dvalid = $args->de_resultado_dvalid;
        }
        $vscore_transacao->in_biometria_cpf = $args->in_biometria_cpf ?? $vscore_transacao->in_biometria_cpf;
        
        if (!$vscore_transacao->save()) {
            throw new Exception('Erro ao atualizar a assinatura do registro.');
        }

        $vscore_transacao->refresh();

        return $vscore_transacao;
    }
}
