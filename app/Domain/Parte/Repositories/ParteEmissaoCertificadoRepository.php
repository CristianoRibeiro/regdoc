<?php

namespace App\Domain\Parte\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;
use Helper;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\Parte\Models\parte_emissao_certificado;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoRepositoryInterface;
use App\Domain\Pedido\Models\pedido;


class ParteEmissaoCertificadoRepository implements ParteEmissaoCertificadoRepositoryInterface
{

    /**
     * @param stdClass $args
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listar(stdClass $args) : \Illuminate\Pagination\LengthAwarePaginator
    {
        $parte_emissao_certificado = new parte_emissao_certificado();
        if($args->nome) {
            $parte_emissao_certificado = $parte_emissao_certificado->where('no_parte', 'ilike', '%' . $args->nome . '%');
        }
        if($args->nu_cpf) {
            $nu_cpf = Helper::somente_numeros($args->nu_cpf);
            $parte_emissao_certificado = $parte_emissao_certificado->where('nu_cpf_cnpj', $nu_cpf);
        }
        if($args->id_tipo_emissao) {
            $parte_emissao_certificado = $parte_emissao_certificado->where('id_parte_emissao_certificado_tipo', $args->id_tipo_emissao);
        }
        return $parte_emissao_certificado->orderBy('dt_cadastro', 'desc')
                                         ->paginate(10,['*'],'todos_registros_pag');
    }

    /**
     * @param int $id_parte_emissao_certificado
     * @return parte_emissao_certificado
     */
    public function buscar(int $id_parte_emissao_certificado) : ?parte_emissao_certificado
    {
        return parte_emissao_certificado::find($id_parte_emissao_certificado);
    }

    /**
     * @param string $nu_cpf_cnpj
     * @return parte_emissao_certificado
     */
    public function buscar_cpf_cnpj(string $nu_cpf_cnpj) : ?parte_emissao_certificado
    {
        return parte_emissao_certificado::where('nu_cpf_cnpj', $nu_cpf_cnpj)->first();
    }

    /**
     * @param stdClass $args
     * @return parte_emissao_certificado
     * @throws Exception
     */
    public function inserir(stdClass $args): parte_emissao_certificado
    {
        $nova_parte_emissao_certificado = new parte_emissao_certificado();
        $nova_parte_emissao_certificado->uuid = Uuid::uuid4();
        $nova_parte_emissao_certificado->id_parte_emissao_certificado_situacao = $args->id_parte_emissao_certificado_situacao;
        $nova_parte_emissao_certificado->no_parte = $args->no_parte;
        $nova_parte_emissao_certificado->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $nova_parte_emissao_certificado->nu_telefone_contato = $args->nu_telefone_contato;
        $nova_parte_emissao_certificado->no_email_contato = $args->no_email_contato;
        $nova_parte_emissao_certificado->dt_agendamento = $args->dt_agendamento ?? NULL;
        $nova_parte_emissao_certificado->dt_emissao = $args->dt_emissao ?? NULL;
        $nova_parte_emissao_certificado->dt_situacao = $args->dt_situacao ?? NULL;
        $nova_parte_emissao_certificado->de_problema = $args->de_problema ?? NULL;

        $nova_parte_emissao_certificado->in_cnh = $args->in_cnh ?? NULL;
        $nova_parte_emissao_certificado->dt_nascimento = $args->dt_nascimento ?? NULL;
        $nova_parte_emissao_certificado->nu_cep = $args->nu_cep ?? NULL;
        $nova_parte_emissao_certificado->no_endereco = $args->no_endereco ?? NULL;
        $nova_parte_emissao_certificado->nu_endereco = $args->nu_endereco ?? NULL;
        $nova_parte_emissao_certificado->no_bairro = $args->no_bairro ?? NULL;
        $nova_parte_emissao_certificado->id_cidade = $args->id_cidade ?? NULL;

        $nova_parte_emissao_certificado->id_portal_certificado_vidaas = $args->id_portal_certificado_vidaas ?? NULL;
        $nova_parte_emissao_certificado->id_parte_emissao_certificado_tipo = $args->id_parte_emissao_certificado_tipo ?? NULL;
        $nova_parte_emissao_certificado->id_usuario_cad = Auth::User()->id_usuario ?? 1;
        $nova_parte_emissao_certificado->dt_atualizacao = $args->dt_atualizacao ?? Carbon::now();

        $nova_parte_emissao_certificado->de_situacao_ticket = $args->de_situacao_ticket ?? NULL;

        $nova_parte_emissao_certificado->de_observacoes_envio = $args->de_observacoes_envio ?? NULL;
        $nova_parte_emissao_certificado->id_pedido = $args->id_pedido ?? NULL;
        $nova_parte_emissao_certificado->de_observacao_situacao = $args->de_observacao_situacao ?? NULL;
        $nova_parte_emissao_certificado->in_atualizacao_automatica = $args->in_atualizacao_automatica ?? 'S';

        if (!$nova_parte_emissao_certificado->save()) {
            throw new Exception('Erro ao salvar a parte_emissao_certificado.');
        }

        return $nova_parte_emissao_certificado;
    }

    /**
     * @param parte_emissao_certificado $parte_emissao_certificado
     * @param stdClass $args
     * @return parte_emissao_certificado
     * @throws Exception
     */
    public function alterar(parte_emissao_certificado $parte_emissao_certificado, stdClass $args) : parte_emissao_certificado
    {
        if (isset($args->id_parte_emissao_certificado_situacao)) {
            $parte_emissao_certificado->id_parte_emissao_certificado_situacao = $args->id_parte_emissao_certificado_situacao;
        }
        if (isset($args->nu_telefone_contato)) {
            $parte_emissao_certificado->nu_telefone_contato = $args->nu_telefone_contato;
        }
        if (isset($args->no_email_contato)) {
            $parte_emissao_certificado->no_email_contato = $args->no_email_contato;
        }
        if (isset($args->dt_agendamento)) {
            $parte_emissao_certificado->dt_agendamento = $args->dt_agendamento;
        }
        if (isset($args->dt_emissao)) {
            $parte_emissao_certificado->dt_emissao = $args->dt_emissao;
        }
        if (isset($args->de_problema)) {
            $parte_emissao_certificado->de_problema = $args->de_problema;
        }
        if (isset($args->nu_ticket_vidaas)) {
            $parte_emissao_certificado->nu_ticket_vidaas = $args->nu_ticket_vidaas;
        }
        if (isset($args->de_observacoes_envio)) {
            $parte_emissao_certificado->de_observacoes_envio = $args->de_observacoes_envio;
        }
        if (isset($args->de_situacao_ticket)) {
            $parte_emissao_certificado->de_situacao_ticket = $args->de_situacao_ticket;
        }
        if (isset($args->de_observacao_situacao)) {
            $parte_emissao_certificado->de_observacao_situacao = $args->de_observacao_situacao;
        }
        if (isset($args->dt_situacao)) {
            $parte_emissao_certificado->dt_situacao = $args->dt_situacao;
        }
        if (isset($args->in_atualizacao_automatica)) {
            $parte_emissao_certificado->in_atualizacao_automatica = $args->in_atualizacao_automatica;
        }
       
        $parte_emissao_certificado->dt_atualizacao = Carbon::now();

        if (!$parte_emissao_certificado->save()) {
            throw new Exception('Erro ao alterar a emissão do certificado.');
        }

        return $parte_emissao_certificado;
    }

    public function busca_todas_emissoes_pedido(pedido $pedido): Collection
    {
        $partes = parte_emissao_certificado::where('id_pedido', $pedido->id_pedido)->get();

        return $partes;
    }
}
