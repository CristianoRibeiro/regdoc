<?php

namespace App\Http\Requests\RegistroFiduciario\Partes;

use Illuminate\Foundation\Http\FormRequest;

use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;

class EditarRegistroParte extends FormRequest
{
    private RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface;

    public function __construct(RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface)
    {
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
    }

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tipo_parte = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($this->id_registro_tipo_parte_tipo_pessoa);

        // Verifica se a parte é simples ou não (isso influencia os campos)
        if ($tipo_parte->in_simples === 'S') {
            $regras = [
                // Dados da parte simples
                'no_parte' => 'required|max:60',

                'nu_telefone_contato' => 'required|max:15',
                'nu_telefone_contato_adicional' => 'nullable|max:25|min:6',
                'no_email_contato' => 'required|email:rfc,dns|max:100',

                'nu_cep' => 'nullable|formato_cep',
                'no_endereco' => 'nullable|max:200',
                'nu_endereco' => 'nullable|max:10',
                'no_bairro' => 'nullable|max:60',
                'id_cidade' => 'nullable|exists:cidade,id_cidade'
            ];

            // Se for credor, adiciona a regra de uuid_procuracao
            if ($tipo_parte->in_procuracao === 'S') {
                $regras = $regras + ['uuid_procuracao' => 'required'];
            }

            return $regras;
        } else {
            $regras = [
                // Tipo da Pessoa
                'tp_pessoa' => 'required|in:F,J',

                // Dados da Parte - Pessoa Física
                'no_parte' => 'nullable|required_if:tp_pessoa,F|max:60',

                // Dados da Parte - Pessoa Jurídica
                'no_razao_social' => 'nullable|required_if:tp_pessoa,J|max:60',

                // Dados de Contato
                'nu_telefone_contato' => 'required',
                'nu_telefone_contato_adicional' => 'nullable|max:25|min:6',
                'no_email_contato' => 'required|email:rfc,dns|max:100',
            ];

            if ($this->in_completado == 'S') {
                $regras = $regras + [
                    // Dados da Parte - Pessoa Física
                    'no_nacionalidade' => 'nullable|max:50',
                    'tp_sexo' => 'nullable|in:F,M',
                    'no_profissao' => 'nullable|max:150',
                    'in_menor_idade' => 'nullable|in:S,N',
                    'id_registro_fiduciario_parte_capacidade_civil' => 'nullable|exists:registro_fiduciario_parte_capacidade_civil,id_registro_fiduciario_parte_capacidade_civil',
                    'no_filiacao1' => 'nullable|max:200',
                    'no_filiacao2' => 'nullable|max:200',
                    'no_tipo_documento' => 'nullable|max:50',
                    'numero_documento' => 'nullable|max:50',
                    'no_orgao_expedidor_documento' => 'nullable|max:60',
                    'dt_nascimento' => 'nullable|date_format:d/m/Y',

                    // Dados da Parte (Comum aos 2 tipos)
                    'fracao' => 'nullable|max:100',

                    // Endereço
                    'nu_cep' => 'nullable|formato_cep',
                    'no_endereco' => 'nullable|max:200',
                    'nu_endereco' => 'nullable|max:10',
                    'no_bairro' => 'nullable|max:60',
                    'id_cidade' => 'nullable|exists:cidade,id_cidade',
                ];
            } else {
                $regras = $regras + [
                    'nu_cep' => 'nullable|formato_cep',
                    'no_endereco' => 'nullable|max:200',
                    'nu_endereco' => 'nullable|max:10',
                    'no_bairro' => 'nullable|max:60',
                    'id_cidade' => 'nullable|exists:cidade,id_cidade',
                ];
            }

            return $regras;
        }
    }

    public function attributes()
    {
        return [
            'tp_pessoa' => 'Tipo da pessoa',
            'in_completado' => 'a parte está completa',

            // Dados da Parte - Pessoa Física
            'no_parte' => 'Nome completo',
            'nu_cpf' => 'CPF',
            'no_nacionalidade' => 'Nacionalidade',
            'tp_sexo' => 'Gênero',
            'no_profissao' => 'Profissão',
            'in_menor_idade' => 'É menor de idade?',
            'id_registro_fiduciario_parte_capacidade_civil' => 'Capacidade civil',
            'no_filiacao1' => 'Filiação 1',
            'no_filiacao2' => 'Filiação 2',
            'dt_nascimento' => 'Data de nascimento',

            'no_tipo_documento' => 'Tipo de documento',
            'numero_documento' => 'Número do documento',
            'no_orgao_expedidor_documento' => 'Órgão / UF expedidor do documento',

            // Dados da Parte - Pessoa Jurídica
            'no_razao_social' => 'Razão Social',
            'nu_cnpj' => 'CNPJ',

            // Dados da Parte (Comum aos 2 tipos)
            'fracao' => 'Fração',

            // Endereço
            'nu_cep' => 'CEP do endereço',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número do endereço',
            'no_bairro' => 'Bairro do endereço',
            'id_cidade' => 'Cidade do endereço',

            'nu_telefone_contato' => 'Telefone',
            'no_email_contato' => 'E-mail',
        ];
    }
}
