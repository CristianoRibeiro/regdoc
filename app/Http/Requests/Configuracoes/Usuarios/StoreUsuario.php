<?php

namespace App\Http\Requests\Configuracoes\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use Helper;
use Auth;

class StoreUsuario extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nu_cpf_cnpj' => Helper::somente_numeros($this->nu_cpf_cnpj)
        ]);
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
        $regras = [
            'no_usuario' => 'required|string|max:60',
			'email_usuario' => [
                'required',
                'email',
                'max:100',
                Rule::unique('usuario', 'email_usuario')->where(function ($query) {
                	$query->where('in_cliente', 'N');
            	})
            ],
            'nu_cpf_cnpj' => [
                'required',
                'string',
                'max:14',
                Rule::unique('pessoa', 'nu_cpf_cnpj')->where(function($query) {
                    $query->where('pessoa.id_tipo_pessoa', '<>', config('constants.USUARIO.ID_TIPO_PESSOA_CLIENTE'));
                })
            ],
            'in_usuario_master' => 'nullable|string|in:S'
        ];

        if (in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, config('constants.USUARIO.ID_TIPO_PESSOA_ADDVINCULO'))) {
            $regras = $regras + [
                'id_pessoa' => 'required|array',
                'id_pessoa.*' => 'integer|exists:pessoa,id_pessoa'
            ];
        }

        return $regras;
    }

    public function messages()
    {
        return [
            'nu_cpf_cnpj.unique' => 'O campo :attribute informado já está em uso. Por favor insira um novo vínculo.',
            'id_pessoa.*.exists' => 'Um dos vínculos informado não existe no banco de dados.'
        ];
    }

    public function attributes()
    {
        return [
            'no_usuario' => 'Nome completo',
            'nu_cpf_cnpj' => 'CPF',
			'email_usuario' => 'E-mail',
            'id_pessoa' => 'Vínculos',
            'in_usuario_master' => 'Inserir como usuário master'
        ];
    }
}
