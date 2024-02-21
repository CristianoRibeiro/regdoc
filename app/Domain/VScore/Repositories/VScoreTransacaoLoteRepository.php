<?php

namespace App\Domain\VScore\Repositories;

use Exception;
use stdClass;
use Auth;
use Str;
use Carbon\Carbon;

use App\Domain\VScore\Models\vscore_transacao_lote;

use App\Domain\VScore\Contracts\VScoreTransacaoLoteRepositoryInterface;

class VScoreTransacaoLoteRepository implements VScoreTransacaoLoteRepositoryInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        $vscore_transacao_lotes = new vscore_transacao_lote();

        if (isset($filtros->uuid)) {
            $vscore_transacao_lotes = $vscore_transacao_lotes->where('uuid', $filtros->uuid);
        }
        if (isset($filtros->data_cadastro_ini) and isset($filtros->data_cadastro_fim)) {
            $data_cadastro_ini = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_ini)->startOfDay();
            $data_cadastro_fim = Carbon::createFromFormat('d/m/Y', $filtros->data_cadastro_fim)->endOfDay();

            $vscore_transacao_lotes = $vscore_transacao_lotes->whereBetween('dt_cadastro', [$data_cadastro_ini, $data_cadastro_fim]);
        }
        if (isset($filtros->data_finalizacao_ini) and isset($filtros->data_finalizacao_fim)) {
            $data_finalizacao_ini = Carbon::createFromFormat('d/m/Y', $filtros->data_finalizacao_ini)->startOfDay();
            $data_finalizacao_fim = Carbon::createFromFormat('d/m/Y', $filtros->data_finalizacao_fim)->endOfDay();

            $vscore_transacao_lotes = $vscore_transacao_lotes->whereBetween('dt_cadastro', [$data_finalizacao_ini, $data_finalizacao_fim]);
        }
        if (isset($filtros->in_completado)) {
            $vscore_transacao_lotes = $vscore_transacao_lotes->where('in_completado', $filtros->in_completado);
        }
        if (($filtros->id_pessoa ?? 0)>0) {
            $vscore_transacao_lotes = $vscore_transacao_lotes->where('id_pessoa_origem', $filtros->id_pessoa);
        }

        return $vscore_transacao_lotes->orderBy('dt_cadastro', 'DESC');
    }

    /**
     * @param int $id_vscore_transacao_lote
     * @return vscore_transacao_lote|null
     */
    public function buscar(int $id_vscore_transacao_lote) : ?vscore_transacao_lote
    {
        return vscore_transacao_lote::findOrFail($id_vscore_transacao_lote);
    }

    /**
     * @param string $uuid
     * @return vscore_transacao_lote|null
     */
    public function buscar_uuid(string $uuid) : ?vscore_transacao_lote
    {
        return vscore_transacao_lote::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * @param stdClass $args
     * @return vscore_transacao_lote
     * @throws Exception
     */
    public function inserir(stdClass $args) : vscore_transacao_lote
    {

        $vscore_transacao_lote = new vscore_transacao_lote();
        $vscore_transacao_lote->uuid = Str::uuid();
        $vscore_transacao_lote->in_completado = $args->in_completado ?? 'N';
        $vscore_transacao_lote->url_notificacao = $args->url_notificacao ?? false;
        $vscore_transacao_lote->id_pessoa_origem = Auth::User()->pessoa_ativa->id_pessoa;
        $vscore_transacao_lote->id_usuario_cad = Auth::User()->id_usuario;

        if (!$vscore_transacao_lote->save()) {
            throw new Exception('Erro ao salvar o Lote de Transação do V/Score.');
        }

        return $vscore_transacao_lote;
    }

    /**
     * @param vscore_transacao_lote $vscore_transacao_lote
     * @param stdClass $args
     * @return vscore_transacao_lote
     * @throws Exception
     */
    public function alterar(vscore_transacao_lote $vscore_transacao_lote, stdClass $args): vscore_transacao_lote
    {
        if (isset($args->in_completado)) {
            $vscore_transacao_lote->in_completado = $args->in_completado;
        }
        if (isset($args->dt_finalizacao)) {
            $vscore_transacao_lote->dt_finalizacao = $args->dt_finalizacao;
        }
        if (!$vscore_transacao_lote->save()) {
            throw new Exception('Erro ao atualizar a assinatura do registro.');
        }

        $vscore_transacao_lote->refresh();

        return $vscore_transacao_lote;
    }
}
