<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class SalvarRegistroPartePreCadastro extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $partes_simples = [
            config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TESTEMUNHA'),
            config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR')
        ];
        if (in_array($this->id_tipo_parte_registro_fiduciario, $partes_simples)) {
            $regras = [
                // Dados da Parte - Pessoa Física
                'no_parte' => 'required|max:60',
                'nu_cpf' => 'required|max:14|cpf',
                'tp_sexo' => 'required|in:F,M',

                'nu_telefone_contato' => 'required|max:15',
                'no_email_contato' => 'required|email:rfc,dns|max:100',
            ];

            if ($this->id_tipo_parte_registro_fiduciario==config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR')) {
                $regras['id_registro_fiduciario_parte_tipo_instrumento'] = 'required|exists:registro_fiduciario_parte_tipo_instrumento,id_registro_fiduciario_parte_tipo_instrumento';
                $regras['nu_instrumento'] = 'required|max:20';
                $regras['no_instrumento_orgao'] = 'required|max:50';
                $regras['nu_instrumento_livro'] = 'required|max:10';
                $regras['nu_instrumento_folha'] = 'required';
                $regras['nu_instrumento_registro'] = 'required';
                $regras['dt_instrumento_registro'] = 'required|date_format:d/m/Y';
            }

            return $regras;
        } else {
            return [
                // Dados da Parte - Pessoa Física
                'no_parte' => 'nullable|required_if:tp_pessoa,F|max:60',
                'nu_cpf' => 'nullable|required_if:tp_pessoa,F|max:14|cpf',
                'tp_sexo' => 'nullable|required_if:tp_pessoa,F|in:F,M',

                // Dados da Parte - Pessoa Física - Estado Civil
                'no_estado_civil' => 'nullable|required_if:tp_pessoa,F',
                'no_regime_bens' => 'nullable|required_if:no_estado_civil,Casado|required_if:no_estado_civil,Separado',
                'in_conjuge_ausente' => 'nullable|required_if:no_estado_civil,Casado|required_if:no_estado_civil,Separado|in:S,N',
                'cpf_conjuge' => 'nullable|required_if:no_estado_civil,Casado|required_if:no_estado_civil,Separado',
                'dt_casamento' => 'nullable|required_if:no_estado_civil,Casado|required_if:no_estado_civil,Separado|date_format:d/m/Y',

                // Dados da Parte - Pessoa Jurídica
                'no_razao_social' => 'nullable|required_if:tp_pessoa,J|max:60',
                'nu_cnpj' => 'nullable|required_if:tp_pessoa,J|max:18|cnpj',
                'nu_telefone_contato' => 'required',
                'no_email_contato' => 'required|email:rfc,dns|max:100',
            ];
        }
    }

    public function attributes()
    {
        return [
            'tp_pessoa' => 'Tipo da pessoa',

            // Dados da Parte - Pessoa Física
            'no_parte' => 'Nome completo',
            'nu_cpf' => 'CPF',
            'no_nacionalidade' => 'Nacionalidade',
            'tp_sexo' => 'Gênero',

            // Dados da Parte - Pessoa Física - Estado Civil
            'no_estado_civil' => 'Estado civil',
            'no_regime_bens' => 'Regime de bens',
            'in_conjuge_ausente' => 'O cônjuge é ausente?',
            'cpf_conjuge' => 'CPF do cônjuge',
            'dt_casamento' => 'Data de casamento',

            // Dados da Parte - Pessoa Jurídica
            'no_razao_social' => 'Razão Social',
            'nu_cnpj' => 'CNPJ',

            'nu_telefone_contato' => 'Telefone',
            'no_email_contato' => 'E-mail',
        ];
    }
}
