<?php

namespace App\Domain\Documento\Parte\Repositories;

use Exception;
use stdClass;
use Auth;
use Ramsey\Uuid\Uuid;

use App\Domain\Documento\Parte\Models\documento_parte;

use App\Domain\Documento\Parte\Contracts\DocumentoParteRepositoryInterface;

class DocumentoParteRepository implements DocumentoParteRepositoryInterface
{
    /**
     * @param int $id_documento_parte
     * @return documento_parte|null
    */
    public function buscar(int $id_documento_parte) : ?documento_parte
    {
        return documento_parte::find($id_documento_parte);
    }

    /**
     * @param string $uuid
     * @return documento_parte|null
     */
    public function buscar_uuid(string $uuid) : ?documento_parte
    {
        return documento_parte::where('uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return documento_parte
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_parte
    {
        $nova_documento_parte = new documento_parte();
        $nova_documento_parte->uuid = Uuid::uuid4();
        $nova_documento_parte->id_documento_parte_tipo = $args->id_documento_parte_tipo;
        $nova_documento_parte->id_documento = $args->id_documento;
        $nova_documento_parte->no_parte = $args->no_parte;
        $nova_documento_parte->no_fantasia = $args->no_fantasia ?? NULL;
        $nova_documento_parte->tp_pessoa = $args->tp_pessoa;
        $nova_documento_parte->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $nova_documento_parte->id_tipo_documento_identificacao = $args->id_tipo_documento_identificacao ?? NULL;
        $nova_documento_parte->nu_documento_identificacao = $args->nu_documento_identificacao ?? NULL;
        $nova_documento_parte->no_documento_identificacao = $args->no_documento_identificacao ?? NULL;
        $nova_documento_parte->id_nacionalidade = $args->id_nacionalidade ?? NULL;
        $nova_documento_parte->id_estado_civil = $args->id_estado_civil ?? NULL;
        $nova_documento_parte->no_endereco = $args->no_endereco ?? NULL;
        $nova_documento_parte->nu_endereco = $args->nu_endereco ?? NULL;
        $nova_documento_parte->no_bairro = $args->no_bairro ?? NULL;
        $nova_documento_parte->no_complemento = $args->no_complemento ?? NULL;
        $nova_documento_parte->nu_cep = $args->nu_cep ?? NULL;
        $nova_documento_parte->id_cidade = $args->id_cidade ?? NULL;
        $nova_documento_parte->nu_telefone_contato = $args->nu_telefone_contato ?? NULL;
        $nova_documento_parte->no_email_contato = $args->no_email_contato ?? NULL;
        $nova_documento_parte->in_emitir_certificado = $args->in_emitir_certificado ?? 'N';
        $nova_documento_parte->no_responsavel = $args->no_responsavel ?? NULL;
        $nova_documento_parte->de_outorgados = $args->de_outorgados ?? NULL;
        $nova_documento_parte->in_assinatura_parte = $args->in_assinatura_parte ?? 'N';
        $nova_documento_parte->id_usuario_cad = Auth::User()->id_usuario;
        if (!$nova_documento_parte->save()) {
            throw new Exception('Erro ao salvar a parte do documento.');
        }

        return $nova_documento_parte;
    }

     /**
     * @param documento_parte $documento_parte
     * @param stdClass $args
     * @return documento_parte
     * @throws Exception
     */
    public function alterar(documento_parte $documento_parte, stdClass $args): documento_parte
    {
        if (isset($args->no_parte)) {
            $documento_parte->no_parte = $args->no_parte;
        }
        if (isset($args->no_fantasia)) {
            $documento_parte->no_fantasia = $args->no_fantasia;
        }
        if (isset($args->nu_cpf_cnpj)) {
            $documento_parte->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        }
        if (isset($args->id_tipo_documento_identificacao)) {
            $documento_parte->id_tipo_documento_identificacao = $args->id_tipo_documento_identificacao;
        }
        if (isset($args->nu_documento_identificacao)) {
            $documento_parte->nu_documento_identificacao = $args->nu_documento_identificacao;
        }
        if (isset($args->no_documento_identificacao)) {
            $documento_parte->no_documento_identificacao = $args->no_documento_identificacao;
        }
        if (isset($args->id_nacionalidade)) {
            $documento_parte->id_nacionalidade = $args->id_nacionalidade;
        }
        if (isset($args->id_estado_civil)) {
            $documento_parte->id_estado_civil = $args->id_estado_civil;
        }
        if (isset($args->no_endereco)) {
            $documento_parte->no_endereco = $args->no_endereco;
        }
        if (isset($args->nu_endereco)) {
            $documento_parte->nu_endereco = $args->nu_endereco;
        }
        if (isset($args->no_bairro)) {
            $documento_parte->no_bairro = $args->no_bairro;
        }
        if (isset($args->no_complemento)) {
            $documento_parte->no_complemento = $args->no_complemento;
        }
        if (isset($args->nu_cep)) {
            $documento_parte->nu_cep = $args->nu_cep;
        }
        if (isset($args->id_cidade)) {
            $documento_parte->id_cidade = $args->id_cidade;
        }
        if (isset($args->nu_telefone_contato)) {
            $documento_parte->nu_telefone_contato = $args->nu_telefone_contato;
        }
        if (isset($args->no_email_contato)) {
            $documento_parte->no_email_contato = $args->no_email_contato;
        }
        if (isset($args->in_emitir_certificado)) {
            $documento_parte->in_emitir_certificado = $args->in_emitir_certificado;
        }
        if (isset($args->no_responsavel)) {
            $documento_parte->no_responsavel = $args->no_responsavel;
        }
        if (isset($args->de_outorgados)) {
            $documento_parte->de_outorgados = $args->de_outorgados;
        }
        if (isset($args->in_assinatura_parte)) {
            $documento_parte->in_assinatura_parte = $args->in_assinatura_parte;
        }

        if (!$documento_parte->save()) {
            throw new Exception('Erro ao atualizar a parte do documento.');
        }

        $documento_parte->refresh();

        return $documento_parte;
    }

    /**
     * @param stdClass $args
     * @return documento_parte
     */
    public function buscar_alterar(stdClass $args): documento_parte
    {
        $documento_parte = $this->buscar($args->id_documento_parte);
        if (!$documento_parte)
            throw new Exception('A parte do documento não foi encontrada');

        return $this->alterar($documento_parte, $args);
    }
}
