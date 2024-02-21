<?php

namespace App\Http\Requests\RegistroFiduciario\TempParte;

use Illuminate\Foundation\Http\FormRequest;

use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;

class UpdateRegistroFiduciarioTempParte extends FormRequest
{
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
     * @param $tipoParte
     * @return bool
     */
    private function checkType($tipoParte){
        if ($tipoParte->id_registro_fiduciario_tipo == 2 || $tipoParte->id_registro_fiduciario_tipo == 22) {
            return true;
        }
        return false;
    }

    /**
     * @param $tipoParte
     * @return string
     */
    private function checkEstadoCivil($tipoParte)
    {
        if ($this->checkType($tipoParte)) {
            return 'nullable|required_if:no_estado_civil,Casado|required_if:no_estado_civil,Separado';
        }
        return 'nullable|required_if:no_estado_civil,Casado|required_if:no_estado_civil,Separado|required_if:no_estado_civil,Separado judicialmente|required_if:no_estado_civil,União estável';
    }

    /**
     * @param $tipoParte
     * @return string
     */
    private function checkCpfConjuge($tipoParte)
    {
        if ($this->checkType($tipoParte)) {
            if (in_array($this->no_regime_bens,['Comunhão parcial de bens','Comunhão universal de bens','Participação final nos aquestos'])) return 'nullable';
        }
        return  'nullable|required_if:no_regime_bens,Comunhão parcial de bens|required_if:no_regime_bens,Comunhão universal de bens|required_if:no_regime_bens,Participação final nos aquestos|';
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
        if ($tipo_parte->in_simples=='S') {
            $regras = [
                'hash' => 'required',

                // Dados da parte simples
                'no_parte' => 'required|max:60',
                'nu_cpf' => 'required|max:14|cpf',

                'nu_telefone_contato' => 'required|max:15',
                'no_email_contato' => 'required|email:rfc,dns|max:100',

                'nu_cep' => 'nullable|formato_cep',
                'no_endereco' => 'nullable|max:200',
                'nu_endereco' => 'nullable|max:10',
                'no_bairro' => 'nullable|max:60',
                'id_cidade' => 'nullable|exists:cidade,id_cidade',
            ];

            // Se for gerente, adiciona a regra de uuid_procuracao
            if ($tipo_parte->in_procuracao=='S') {
                $regras = $regras + ['uuid_procuracao' => 'required'];
            }

            return $regras;
        } else {
            return [
                'hash' => 'required',

                // Tipo da Pessoa
                'tp_pessoa' => 'required|in:F,J',

                // Dados da Parte - Pessoa Física
                'no_parte' => 'nullable|required_if:tp_pessoa,F|max:60',
                'nu_cpf' => 'nullable|required_if:tp_pessoa,F|max:14|cpf',

                // Dados da Parte - Pessoa Física - Estado Civil
                'no_estado_civil' => 'nullable|required_if:tp_pessoa,F',
                'no_regime_bens' => $this->checkEstadoCivil($tipo_parte),
                'in_conjuge_ausente' => 'nullable|required_if:no_regime_bens,Comunhão parcial de bens|required_if:no_regime_bens,Comunhão universal de bens|required_if:no_regime_bens,Participação final nos aquestos|in:S,N',
                'cpf_conjuge' => $this->checkCpfConjuge($tipo_parte),
                'dt_casamento' => 'nullable|date_format:d/m/Y',

                // Dados da Parte - Pessoa Jurídica
                'no_razao_social' => 'nullable|required_if:tp_pessoa,J|max:60',
                'nu_cnpj' => 'nullable|required_if:tp_pessoa,J|max:18|cnpj',

                // Dados de Contato
                'nu_telefone_contato' => 'required',
                'no_email_contato' => 'required|email:rfc,dns|max:100',

                'nu_cep' => 'nullable|formato_cep',
                'no_endereco' => 'nullable|max:200',
                'nu_endereco' => 'nullable|max:10',
                'no_bairro' => 'nullable|max:60',
                'id_cidade' => 'nullable|exists:cidade,id_cidade',
            ];
        }
    }

    public function attributes()
    {
        return [
            // Tipo da Pessoa
            'tp_pessoa' => 'Tipo da pessoa',

            // Dados da Parte - Pessoa Física
            'no_parte' => 'Nome completo',
            'nu_cpf' => 'CPF',

            // Dados da Parte - Pessoa Física - Estado Civil
            'no_estado_civil' => 'Estado civil',
            'no_regime_bens' => 'Regime de bens',
            'in_conjuge_ausente' => 'O cônjuge é ausente?',
            'cpf_conjuge' => 'CPF do cônjuge',
            'dt_casamento' => 'Data de casamento',

            // Dados da Parte - Pessoa Jurídica
            'no_razao_social' => 'Razão Social',
            'nu_cnpj' => 'CNPJ',

            // Dados de Contato
            'nu_telefone_contato' => 'Telefone',
            'no_email_contato' => 'E-mail',

            'uuid_procuracao' => 'Procuração',

            // Dados do Endereço
            'nu_cep' => 'CEP',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número do endereço',
            'no_bairro' => 'Bairro',
            'id_estado' => 'Estado',
            'id_cidade' => 'Cidade',
        ];
    }
}
