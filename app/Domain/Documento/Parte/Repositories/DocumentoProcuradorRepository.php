<?php

namespace App\Domain\Documento\Parte\Repositories;

use Exception;
use stdClass;
use Auth;
use Ramsey\Uuid\Uuid;

use App\Domain\Documento\Parte\Models\documento_procurador;

use App\Domain\Documento\Parte\Contracts\DocumentoProcuradorRepositoryInterface;

class DocumentoProcuradorRepository implements DocumentoProcuradorRepositoryInterface
{
    /**
     * @param int $id_documento_procurador
     * @return documento_procurador|null
    */
    public function buscar(int $id_documento_procurador) : ?documento_procurador
    {
        return documento_procurador::find($id_documento_procurador);
    }

    /**
     * @param string $uuid
     * @return documento_procurador|null
     */
    public function buscar_uuid(string $uuid) : ?documento_procurador
    {
        return documento_procurador::where('uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return documento_procurador
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_procurador
    {
        $novo_documento_procurador = new documento_procurador();
        $novo_documento_procurador->uuid = Uuid::uuid4();
        $novo_documento_procurador->id_documento_parte = $args->id_documento_parte;
        $novo_documento_procurador->no_procurador = $args->no_procurador;
        $novo_documento_procurador->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $novo_documento_procurador->id_tipo_documento_identificacao = $args->id_tipo_documento_identificacao ?? NULL;
        $novo_documento_procurador->nu_documento_identificacao = $args->nu_documento_identificacao ?? NULL;
        $novo_documento_procurador->no_documento_identificacao = $args->no_documento_identificacao ?? NULL;
        $novo_documento_procurador->id_nacionalidade = $args->id_nacionalidade ?? NULL;
        $novo_documento_procurador->no_profissao = $args->no_profissao ?? NULL;
        $novo_documento_procurador->id_estado_civil = $args->id_estado_civil ?? NULL;
        $novo_documento_procurador->no_endereco = $args->no_endereco ?? NULL;
        $novo_documento_procurador->nu_endereco = $args->nu_endereco ?? NULL;
        $novo_documento_procurador->no_bairro = $args->no_bairro ?? NULL;
        $novo_documento_procurador->no_complemento = $args->no_complemento ?? NULL;
        $novo_documento_procurador->nu_cep = $args->nu_cep ?? NULL;
        $novo_documento_procurador->id_cidade = $args->id_cidade ?? NULL;
        $novo_documento_procurador->nu_telefone_contato = $args->nu_telefone_contato ?? NULL;
        $novo_documento_procurador->no_email_contato = $args->no_email_contato ?? NULL;
        $novo_documento_procurador->in_emitir_certificado = $args->in_emitir_certificado ?? 'N';
        $novo_documento_procurador->id_usuario_cad = Auth::User()->id_usuario;
        if (!$novo_documento_procurador->save()) {
            throw new Exception('Erro ao salvar o procurador do documento.');
        }

        return $novo_documento_procurador;
    }

     /**
     * @param documento_procurador $documento_procurador
     * @param stdClass $args
     * @return documento_procurador
     * @throws Exception
     */
    public function alterar(documento_procurador $documento_procurador, stdClass $args): documento_procurador
    {
        if (isset($args->no_parte)) {
            $documento_procurador->no_procurador = $args->no_procurador;
        }
        if (isset($args->nu_cpf_cnpj)) {
            $documento_procurador->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        }
        if (isset($args->id_tipo_documento_identificacao)) {
            $documento_procurador->id_tipo_documento_identificacao = $args->id_tipo_documento_identificacao;
        }
        if (isset($args->nu_documento_identificacao)) {
            $documento_procurador->nu_documento_identificacao = $args->nu_documento_identificacao;
        }
        if (isset($args->no_documento_identificacao)) {
            $documento_procurador->no_documento_identificacao = $args->no_documento_identificacao;
        }
        if (isset($args->id_nacionalidade)) {
            $documento_procurador->id_nacionalidade = $args->id_nacionalidade;
        }
        if (isset($args->id_estado_civil)) {
            $documento_procurador->id_estado_civil = $args->id_estado_civil;
        }
        if (isset($args->no_endereco)) {
            $documento_procurador->no_endereco = $args->no_endereco;
        }
        if (isset($args->nu_endereco)) {
            $documento_procurador->nu_endereco = $args->nu_endereco;
        }
        if (isset($args->no_bairro)) {
            $documento_procurador->no_bairro = $args->no_bairro;
        }
        if (isset($args->no_complemento)) {
            $documento_procurador->no_complemento = $args->no_complemento;
        }
        if (isset($args->nu_cep)) {
            $documento_procurador->nu_cep = $args->nu_cep;
        }
        if (isset($args->id_cidade)) {
            $documento_procurador->id_cidade = $args->id_cidade;
        }
        if (isset($args->nu_telefone_contato)) {
            $documento_procurador->nu_telefone_contato = $args->nu_telefone_contato;
        }
        if (isset($args->no_email_contato)) {
            $documento_procurador->no_email_contato = $args->no_email_contato;
        }
        if (isset($args->in_emitir_certificado)) {
            $documento_procurador->in_emitir_certificado = $args->in_emitir_certificado;
        }
        if (isset($args->no_responsavel)) {
            $documento_procurador->no_responsavel = $args->no_responsavel;
        }
        if (isset($args->de_outorgados)) {
            $documento_procurador->de_outorgados = $args->de_outorgados;
        }

        if (!$documento_procurador->save()) {
            throw new Exception('Erro ao atualizar o procurador do documento.');
        }

        $documento_procurador->refresh();

        return $documento_procurador;
    }

    /**
     * @param stdClass $args
     * @return documento_procurador
     */
    public function buscar_alterar(stdClass $args): documento_procurador
    {
        $documento_procurador = $this->buscar($args->id_documento_procurador);
        if (!$documento_procurador)
            throw new Exception('O procurador do documento não foi encontrada');

        return $this->alterar($documento_procurador, $args);
    }
}
