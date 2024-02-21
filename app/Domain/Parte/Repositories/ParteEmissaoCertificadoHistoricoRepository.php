<?php

namespace App\Domain\Parte\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;
use Carbon\Carbon;

use App\Domain\Parte\Models\parte_emissao_certificado_historico;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoHistoricoRepositoryInterface;

class ParteEmissaoCertificadoHistoricoRepository implements ParteEmissaoCertificadoHistoricoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return parte_emissao_certificado_historico
     * @throws Exception
     */
    public function inserir(stdClass $args): parte_emissao_certificado_historico
    {
        $nova_parte_emissao_certificado_historico = new parte_emissao_certificado_historico();
        $nova_parte_emissao_certificado_historico->id_parte_emissao_certificado = $args->id_parte_emissao_certificado;
        $nova_parte_emissao_certificado_historico->id_parte_emissao_certificado_situacao = $args->id_parte_emissao_certificado_situacao;
        $nova_parte_emissao_certificado_historico->de_situacao_ticket = $args->de_situacao_ticket ?? NULL;
        $nova_parte_emissao_certificado_historico->dt_historico = $args->dt_historico ?? Carbon::now();
        $nova_parte_emissao_certificado_historico->de_observacao_situacao = $args->de_observacao_situacao ?? NULL;
        $nova_parte_emissao_certificado_historico->dt_situacao = $args->dt_situacao ?? NULL;
        $nova_parte_emissao_certificado_historico->id_usuario_cad = Auth::User()->id_usuario ?? 1;

        if (!$nova_parte_emissao_certificado_historico->save()) {
            throw new Exception('Erro ao salvar a emissão certificado historico.');
        }

        return $nova_parte_emissao_certificado_historico;
    }
}
