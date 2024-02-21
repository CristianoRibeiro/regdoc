<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Auth;
use Exception;

use Ramsey\Uuid\Uuid;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte;

class RegistroFiduciarioParteRepository implements RegistroFiduciarioParteRepositoryInterface
{
    /**
     * @param int|null $id_tipo_parte_registro_fiduciario
     * @param int|null $id_pessoa
     * @return Collection
     */
    public function listar_agrupado(?int $id_tipo_parte_registro_fiduciario, ?int $id_pessoa): Collection
    {
        $registro_fiduciario_parte = registro_fiduciario_parte::select('registro_fiduciario_parte.no_parte', 'registro_fiduciario_parte.nu_cpf_cnpj', 'registro_fiduciario_parte.nu_telefone_contato', 'registro_fiduciario_parte.no_email_contato', 'registro_fiduciario_parte.in_emitir_certificado', 'procuracao.uuid')
            ->leftJoin('procuracao', 'procuracao.id_procuracao', 'registro_fiduciario_parte.id_procuracao');

        if ($id_pessoa) {
            $registro_fiduciario_parte = $registro_fiduciario_parte->join('registro_fiduciario_pedido', 'registro_fiduciario_pedido.id_registro_fiduciario', 'registro_fiduciario_parte.id_registro_fiduciario')
                ->join('pedido', 'pedido.id_pedido', 'registro_fiduciario_pedido.id_pedido')
                ->where('pedido.id_pessoa_origem', $id_pessoa);

        }
        if ($id_tipo_parte_registro_fiduciario) {
            $registro_fiduciario_parte = $registro_fiduciario_parte->where('id_tipo_parte_registro_fiduciario', $id_tipo_parte_registro_fiduciario);
        }
        $registro_fiduciario_parte = $registro_fiduciario_parte->groupBy('registro_fiduciario_parte.no_parte', 'registro_fiduciario_parte.nu_cpf_cnpj', 'registro_fiduciario_parte.nu_telefone_contato', 'registro_fiduciario_parte.no_email_contato', 'registro_fiduciario_parte.in_emitir_certificado', 'procuracao.uuid');

        return $registro_fiduciario_parte->get();
    }

    /**
     * @param int $id_registro_fiduciario_parte
     * @return registro_fiduciario_parte|null
     */
    public function buscar(int $id_registro_fiduciario_parte): ?registro_fiduciario_parte
    {
        return registro_fiduciario_parte::find($id_registro_fiduciario_parte);
    }

    /**
     * @param array $ids_registro_fiduciario_parte
     * @return registro_fiduciario_parte|null
     */
    public function buscar_ids(array $ids_registro_fiduciario_parte): Collection
    {
        return registro_fiduciario_parte::whereIn('id_registro_fiduciario_parte', $ids_registro_fiduciario_parte)->get();
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_parte|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_parte
    {
        return registro_fiduciario_parte::where('uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_parte
    {
        $registro_fiduciario_parte = new registro_fiduciario_parte();
        $registro_fiduciario_parte->uuid = Uuid::uuid4();
        $registro_fiduciario_parte->id_registro_fiduciario = $args->id_registro_fiduciario;
        $registro_fiduciario_parte->id_tipo_parte_registro_fiduciario = $args->id_tipo_parte_registro_fiduciario;
        $registro_fiduciario_parte->no_parte = $args->no_parte;
        $registro_fiduciario_parte->in_parte_master = $args->in_parte_master ?? 'N';
        $registro_fiduciario_parte->tp_sexo = $args->tp_sexo ?? NULL;
        $registro_fiduciario_parte->no_nacionalidade = $args->no_nacionalidade ?? NULL;
        $registro_fiduciario_parte->no_profissao = $args->no_profissao ?? NULL;
        $registro_fiduciario_parte->no_tipo_documento = $args->no_tipo_documento ?? NULL;
        $registro_fiduciario_parte->numero_documento = $args->numero_documento ?? NULL;
        $registro_fiduciario_parte->no_orgao_expedidor_documento = $args->no_orgao_expedidor_documento ?? NULL;
        $registro_fiduciario_parte->uf_orgao_expedidor_documento = $args->uf_orgao_expedidor_documento ?? NULL;
        $registro_fiduciario_parte->dt_expedicao_documento = $args->dt_expedicao_documento ?? NULL;
        $registro_fiduciario_parte->tp_pessoa = $args->tp_pessoa;
        $registro_fiduciario_parte->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $registro_fiduciario_parte->nu_cep = $args->nu_cep ?? NULL;
        $registro_fiduciario_parte->no_endereco = $args->no_endereco ?? NULL;
        $registro_fiduciario_parte->nu_endereco = $args->nu_endereco ?? NULL;
        $registro_fiduciario_parte->no_bairro = $args->no_bairro ?? NULL;
        $registro_fiduciario_parte->id_cidade = $args->id_cidade ?? NULL;
        $registro_fiduciario_parte->no_estado_civil = $args->no_estado_civil ?? NULL;
        $registro_fiduciario_parte->no_regime_bens = $args->no_regime_bens ?? NULL;
        $registro_fiduciario_parte->nu_telefone_contato = $args->nu_telefone_contato ?? NULL;
        $registro_fiduciario_parte->no_email_contato = mb_strtolower($args->no_email_contato ?? NULL, 'UTF-8');
        $registro_fiduciario_parte->id_usuario_cad = Auth::User()->id_usuario;
        $registro_fiduciario_parte->fracao = $args->fracao ?? NULL;
        $registro_fiduciario_parte->dt_casamento = $args->dt_casamento ?? NULL;
        $registro_fiduciario_parte->in_conjuge_ausente = $args->in_conjuge_ausente  ?? NULL;
        $registro_fiduciario_parte->in_menor_idade = $args->in_menor_idade  ?? NULL;
        $registro_fiduciario_parte->id_registro_fiduciario_parte_capacidade_civil = $args->id_registro_fiduciario_parte_capacidade_civil ?? NULL;
        $registro_fiduciario_parte->no_filiacao1 = $args->no_filiacao1 ?? NULL;
        $registro_fiduciario_parte->no_filiacao2 = $args->no_filiacao2 ?? NULL;
        $registro_fiduciario_parte->dt_nascimento = $args->dt_nascimento ?? NULL;

        $registro_fiduciario_parte->id_registro_fiduciario_parte_tipo_instrumento = $args->id_registro_fiduciario_parte_tipo_instrumento ?? NULL;
        $registro_fiduciario_parte->nu_instrumento = $args->nu_instrumento ?? NULL;
        $registro_fiduciario_parte->no_instrumento_orgao = $args->no_instrumento_orgao ?? NULL;
        $registro_fiduciario_parte->no_instrumento_forma_registro = $args->no_instrumento_forma_registro ?? NULL;
        $registro_fiduciario_parte->nu_instrumento_livro = $args->nu_instrumento_livro ?? NULL;
        $registro_fiduciario_parte->nu_instrumento_folha = $args->nu_instrumento_folha ?? NULL;
        $registro_fiduciario_parte->nu_instrumento_registro = $args->nu_instrumento_registro ?? NULL;
        $registro_fiduciario_parte->dt_instrumento_registro = $args->dt_instrumento_registro ?? NULL;

        $registro_fiduciario_parte->id_construtora = $args->id_construtora ?? NULL;
        $registro_fiduciario_parte->id_procuracao = $args->id_procuracao ?? NULL;
        $registro_fiduciario_parte->in_completado = $args->in_completado ?? 'N';
        $registro_fiduciario_parte->in_emitir_certificado = $args->in_emitir_certificado ?? 'N';
        $registro_fiduciario_parte->in_cnh = $args->in_cnh ?? 'N';
        $registro_fiduciario_parte->id_registro_tipo_parte_tipo_pessoa = $args->id_registro_tipo_parte_tipo_pessoa ?? NULL;

        if (!$registro_fiduciario_parte->save()) {
            throw new Exception('Erro ao salvar a parte do registro.');
        }

        return $registro_fiduciario_parte;
    }

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @param stdClass $args
     * @return registro_fiduciario_parte
     * @throws Exception
     */
    public function alterar(registro_fiduciario_parte $registro_fiduciario_parte, stdClass $args): registro_fiduciario_parte
    {
        if (isset($args->id_tipo_parte_registro_fiduciario)) {
            $registro_fiduciario_parte->id_tipo_parte_registro_fiduciario = $args->id_tipo_parte_registro_fiduciario;
        }
        if (isset($args->no_parte)) {
            $registro_fiduciario_parte->no_parte = $args->no_parte;
        }
        if (isset($args->nu_cpf_cnpj)) {
            $registro_fiduciario_parte->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        }
        if (isset($args->no_estado_civil)) {
            $registro_fiduciario_parte->no_estado_civil = $args->no_estado_civil;
        }
        if (isset($args->no_regime_bens)) {
            $registro_fiduciario_parte->no_regime_bens = $args->no_regime_bens;
        }
        if (isset($args->dt_casamento)) {
            $registro_fiduciario_parte->dt_casamento = $args->dt_casamento;
        }
        if (isset($args->in_conjuge_ausente)) {
            $registro_fiduciario_parte->in_conjuge_ausente = $args->in_conjuge_ausente;
        }
        if (isset($args->tp_pessoa)) {
            $registro_fiduciario_parte->tp_pessoa = $args->tp_pessoa;
        }
        if (isset($args->in_parte_master)) {
            $registro_fiduciario_parte->in_parte_master = $args->in_parte_master;
        }
        if (isset($args->tp_sexo)) {
            $registro_fiduciario_parte->tp_sexo = $args->tp_sexo;
        }
        if (isset($args->no_nacionalidade)) {
            $registro_fiduciario_parte->no_nacionalidade = $args->no_nacionalidade;
        }
        if (isset($args->no_profissao)) {
            $registro_fiduciario_parte->no_profissao = $args->no_profissao;
        }
        if (isset($args->no_tipo_documento)) {
            $registro_fiduciario_parte->no_tipo_documento = $args->no_tipo_documento;
        }
        if (isset($args->numero_documento)) {
            $registro_fiduciario_parte->numero_documento = $args->numero_documento;
        }
        if (isset($args->no_orgao_expedidor_documento)) {
            $registro_fiduciario_parte->no_orgao_expedidor_documento = $args->no_orgao_expedidor_documento;
        }
        if (isset($args->uf_orgao_expedidor_documento)) {
            $registro_fiduciario_parte->uf_orgao_expedidor_documento = $args->uf_orgao_expedidor_documento;
        }
        if (isset($args->dt_expedicao_documento)) {
            $registro_fiduciario_parte->dt_expedicao_documento = $args->dt_expedicao_documento;
        }
        if (isset($args->nu_cep)) {
            $registro_fiduciario_parte->nu_cep = $args->nu_cep;
        }
        if (isset($args->no_endereco)) {
            $registro_fiduciario_parte->no_endereco = $args->no_endereco;
        }
        if (isset($args->nu_endereco)) {
            $registro_fiduciario_parte->nu_endereco = $args->nu_endereco;
        }
        if (isset($args->no_bairro)) {
            $registro_fiduciario_parte->no_bairro = $args->no_bairro;
        }
        if (isset($args->id_cidade)) {
            $registro_fiduciario_parte->id_cidade = $args->id_cidade;
        }
        if (isset($args->nu_telefone_contato)) {
            $registro_fiduciario_parte->nu_telefone_contato = $args->nu_telefone_contato;
        }
        if (isset($args->nu_telefone_contato_adicional)) {
            $registro_fiduciario_parte->nu_telefone_contato_adicional = $args->nu_telefone_contato_adicional;
        }
        if (isset($args->no_email_contato)) {
            $registro_fiduciario_parte->no_email_contato = mb_strtolower($args->no_email_contato, 'UTF-8');
        }
        if (isset($args->fracao)) {
            $registro_fiduciario_parte->fracao = $args->fracao;
        }
        if (isset($args->in_menor_idade)) {
            $registro_fiduciario_parte->in_menor_idade = $args->in_menor_idade ;
        }
        if (isset($args->id_registro_fiduciario_parte_capacidade_civil)) {
            $registro_fiduciario_parte->id_registro_fiduciario_parte_capacidade_civil = $args->id_registro_fiduciario_parte_capacidade_civil;
        }
        if (isset($args->no_filiacao1)) {
            $registro_fiduciario_parte->no_filiacao1 = $args->no_filiacao1;
        }
        if (isset($args->no_filiacao2)) {
            $registro_fiduciario_parte->no_filiacao2 = $args->no_filiacao2;
        }
        if (isset($args->dt_nascimento)) {
            $registro_fiduciario_parte->dt_nascimento = $args->dt_nascimento;
        }
        if (isset($args->id_registro_fiduciario_parte_conjuge)) {
            $registro_fiduciario_parte->id_registro_fiduciario_parte_conjuge = $args->id_registro_fiduciario_parte_conjuge;
        }
        if (isset($args->in_completado)) {
            $registro_fiduciario_parte->in_completado = $args->in_completado;
        }
        if (isset($args->in_emitir_certificado)) {
            $registro_fiduciario_parte->in_emitir_certificado = $args->in_emitir_certificado;
        }
        if (isset($args->id_procuracao)) {
            $registro_fiduciario_parte->id_procuracao = $args->id_procuracao;
        }
        if (isset($args->id_pedido_usuario)) {
            $registro_fiduciario_parte->id_pedido_usuario = $args->id_pedido_usuario;
        }
        if (isset($args->in_cnh)) {
            $registro_fiduciario_parte->in_cnh = $args->in_cnh;
        }
        if (isset($args->id_registro_tipo_parte_tipo_pessoa)) {
            $registro_fiduciario_parte->id_registro_tipo_parte_tipo_pessoa = $args->id_registro_tipo_parte_tipo_pessoa;
        }

        if (!$registro_fiduciario_parte->save()) {
            throw new Exception('Erro ao atualizar a parte do registro.');
        }

        $registro_fiduciario_parte->refresh();

        return $registro_fiduciario_parte;
    }

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @return bool
     * @throws Exception
     */
    public function deletar(registro_fiduciario_parte $registro_fiduciario_parte) : bool
    {
        return $registro_fiduciario_parte->delete();
    }

    /**
     * @param registro_fiduciario_parte $registro_fiduciario_parte
     * @param stdClass $args
     * @return bool
     * @throws Exception
     */
    public function verificar_cpf(stdClass $args) : bool
    {

        $registro_fiduciario_parte = new registro_fiduciario_parte();
        $registro_fiduciario_parte = $registro_fiduciario_parte::where('id_registro_fiduciario', $args->id_registro_fiduciario)
                                                                 ->where('id_tipo_parte_registro_fiduciario', $args->id_tipo_parte_registro_fiduciario)
                                                                 ->where('nu_cpf_cnpj', $args->nu_cpf_cnpj)->first();

        if ($registro_fiduciario_parte)
        {
            return true;
        }
        return false;
    }

    /**
     * @return Collection<registro_fiduciario_parte>
     */
    public function buscar_por_cpf_cnpj(string $nu_cpf_cnpj): Collection
    {
        $registro_fiduciario_parte = registro_fiduciario_parte::where('nu_cpf_cnpj', $nu_cpf_cnpj);

        return $registro_fiduciario_parte->get();
    }
}
