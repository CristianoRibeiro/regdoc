<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\CpfCnpj;

class InserirRegistro extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return bool
     */
    private function checkType(){
        if ($this->tipo_registro == 2 || $this->tipo_registro == 22) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    private function checkEstadoCivil()
    {
        if ($this->checkType()) {
            foreach ($this->partes as $parte) {
                if (in_array($parte['tipo'], [5, 3, 12])) {
                    return 'nullable|required_if:partes.*.estado_civil,2,3|in:1,2,3,4';
                }
            }
        }
        return 'nullable|required_if:partes.*.estado_civil,2,3,4,7|in:1,2,3,4';
    }

    /**
     * @return string
     */
    private function checkCpfConjuge()
    {
        if ($this->checkType()) {
            foreach ($this->partes as $parte) {
                if (in_array($parte['estado_civil'], [2, 3, 4, 7])) {
                    return 'nullable|max:11|cpf';
                }
            }
        }
        return 'nullable|required_if:partes.*.regime_bens,1,2,4|max:11|cpf';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regras = [
            'produto' => 'required|in:fiduciario,garantias',
            'tipo_registro' => 'required|exists:registro_fiduciario_tipo,id_registro_fiduciario_tipo',
            'tipo_insercao' => 'required|in:C,P',
            'cns_cartorio' => 'nullable|exists:serventia,codigo_cns_completo',

            'parceiro' => 'sometimes',
            'parceiro.cnpj' => [
                'required_unless:parceiro,null',
                new CpfCnpj,
                'exists:canal_pdv_parceiro,cnpj_canal_pdv_parceiro'
            ],
            'parceiro.no_pj' => 'nullable',

            'url_notificacao' => 'nullable|url',

            'credor_fiduciario' => 'required|array',
            'credor_fiduciario.cnpj' => 'required_with:credor_fiduciario|cnpj|exists:registro_fiduciario_credor,nu_cpf_cnpj',

            'contrato' => 'required_if:tipo_insercao,C|array',
            'contrato.numero' => 'required_if:tipo_insercao,C|integer',
            'contrato.assinado' => 'required_if:tipo_insercao,C|in:S,N',

            'proposta' => 'required_if:tipo_insercao,P|array',
            'proposta.numero' => 'required_if:tipo_insercao,P|integer',

            'partes' => 'required|array',
            'partes.*.tipo' => 'required|exists:tipo_parte_registro_fiduciario,codigo_tipo_parte_registro_fiduciario',
            'partes.*.tipo_pessoa' => 'required|in:F,J',
            'partes.*.nome' => 'required',
            'partes.*.cpf_cnpj' => [
                'required_if:partes.*.nacionalidade,1',
                'max:14',
                new CpfCnpj
            ],
            'partes.*.telefone_contato' => 'required|max:11',
            'partes.*.emitir_certificado' => 'required|in:S,N',
            'partes.*.email_contato' => 'required|email:rfc,dns',
            'partes.*.regime_bens' => $this->checkEstadoCivil(),
            'partes.*.data_casamento' => 'nullable|date_format:Y-m-d',
            'partes.*.conjuge_ausente' => 'nullable|required_if:partes.*.regime_bens,1,2,4|in:S,N',
            'partes.*.cpf_conjuge' => $this->checkCpfConjuge(),
            'partes.*.procuracao' => 'nullable|required_if:partes.*.tipo,4|exists:procuracao,uuid',
            'partes.*.procuradores.*.nome' => 'required_with:partes.*.procuradores.*',
            'partes.*.procuradores.*.cpf' => 'required_with:partes.*.procuradores.*|max:11|cpf',
            'partes.*.procuradores.*.email_contato' => 'required_with:partes.*.procuradores.*',

            'arquivos' => 'required_if:tipo_insercao,C|array',
            'arquivos.*.tipo' => 'required_if:tipo_insercao,C|exists:tipo_arquivo_grupo_produto,co_tipo_arquivo',
            'arquivos.*.nome' => 'required_if:tipo_insercao,C|max:255',
            'arquivos.*.bytes' => 'required_without:arquivos.*.url',
            'arquivos.*.url' => 'required_without:arquivos.*.bytes',
            'arquivos.*.extensao' => 'required_if:tipo_insercao,C|max:10',
            'arquivos.*.mime_type' => 'required_if:tipo_insercao,C|max:100',
            'arquivos.*.hash' => 'required_if:tipo_insercao,C|max:32'
        ];

        foreach ($this->partes as $key => $parte) {
            if (($parte['tipo_pessoa'] ?? '') == 'F' && in_array(($parte['tipo'] ?? 0), [1,2,5,6,8,9])) {
                $regras += [
                    'partes.' . $key . '.estado_civil' => 'required|in:1,2,3,4,5,6,7'
                ];
            }
        }

        return $regras;
    }

    public function attributes()
    {
        return [
            'produto' => 'Produto',
            'tipo_registro' => 'Tipo do Registro',
            'tipo_insercao' => 'Tipo da inserção',
            'cns_cartorio' => 'CNS do cartório',
            'contrato_assinado' => 'Contrato assinado',
            'url_notificacao' => 'URL de notificação',

            'credor_fiduciario' => 'Credor Fiduciário',
            'credor_fiduciario.cnpj' => 'CNPJ do Credor Fiduciário',

            'contrato' => 'Contrato',
            'contrato.numero' => 'Número do Contrato',

            'proposta' => 'Proposta',
            'proposta.numero' => 'Número da Proposta',

            'partes' => 'Partes',
            'partes.*.tipo' => 'Tipo da Parte',
            'partes.*.tipo_pessoa' => 'Tipo de Pessoa da Parte',
            'partes.*.nome' => 'Nome da Parte',
            'partes.*.cpf_cnpj' => 'CPF / CNPJ da Parte',
            'partes.*.emitir_certificado' => 'Emitir certificado da Parte',
            'partes.*.telefone_contato' => 'Telefone de Contato da Parte',
            'partes.*.email_contato' => 'E-mail de Contato da Parte',
            'partes.*.estado_civil' => 'Estado Civil da Parte',
            'partes.*.regime_bens' => 'Regime de Bens da Parte',
            'partes.*.data_casamento' => 'Data de Casamento da Parte',
            'partes.*.conjuge_ausente' => 'Cônjuge Ausente da Parte',
            'partes.*.cpf_conjuge' => 'CPF do Conjuge da Parte',
            'partes.*.procuracao' => 'Procuração do credor',
            'partes.*.procuradores.*.nome' => 'Nome do Procurador da Parte',
            'partes.*.procuradores.*.cpf' => 'CPF do Procurador da Parte',
            'partes.*.procuradores.*.telefone_contato' => 'Telefone de Contato do Procurador da Parte',
            'partes.*.procuradores.*.email_contato' => 'E-mail de Contato do Procurador da Parte',

            'arquivos' => 'Arquivos',
            'arquivos.*.tipo' => 'Tipo do Arquivo',
            'arquivos.*.nome' => 'Nome do Arquivo',
            'arquivos.*.bytes' => 'Bytes do Arquivo',
            'arquivos.*.url' => 'URL do Arquivo',
            'arquivos.*.tamanho' => 'Tamanho do Arquivo',
            'arquivos.*.extensao' => 'Extensão do Arquivo',
            'arquivos.*.mime_type' => 'Mime Type do Arquivo',
            'arquivos.*.hash' => 'Hash MD5 do Arquivo',
            'arquivos.*.assinatura_digital' => 'Assiantura Digital do Arquivo',
            'parceiro' => 'Parceiro',
            'parceiro.codigo_parceiro' => 'Código do parceiro cadastrado relacionado ao CNPJ informado',
            'parceiro.cnpj_parceiro' => 'CNPJ do Parceiro',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'message' => 'Erro na validação dos campos.',
            'validacao' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
