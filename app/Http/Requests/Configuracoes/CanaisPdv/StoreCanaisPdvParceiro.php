<?php

namespace App\Http\Requests\Configuracoes\CanaisPdv;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use Helper;
use Auth;

class StoreCanaisPdvParceiro extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'cnpj_canal_pdv_parceiro' => Helper::somente_numeros($this->cnpj_canal_pdv_parceiro)
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
            'nome_canal_pdv_parceiro' => 'required|string|max:120',
			'email_canal_pdv_parceiro' => [
                'required',
                'email',
                'max:100',
                Rule::unique('canal_pdv_parceiro', 'email_canal_pdv_parceiro')->where(function ($query) {
                	$query->where('in_canal_pdv_parceiro_ativo', 'S');
            	})
            ],
            'cnpj_canal_pdv_parceiro' => [
                'required',
                'string',
                'max:14',
                Rule::unique('canal_pdv_parceiro', 'cnpj_canal_pdv_parceiro')->where(function($query) {
                    $query->where('in_canal_pdv_parceiro_ativo', 'S');
                })
            ],
            'codigo_canal_pdv_parceiro' => 'nullable|string|max:100',
            'parceiro_canal_pdv_parceiro' => 'required|string|max:120'
        ];


        return $regras;
    }

    public function messages()
    {
        return [
            'cnpj_canal_pdv_parceiro.unique' => 'O campo :attribute informado já está em uso.',
            'email_canal_pdv_parceiro.unique' => 'O campo :attribute informado já está em uso.'
        ];
    }

    public function attributes()
    {
        return [
            'nome_canal_pdv_parceiro' => 'Nome',
            'cnpj_canal_pdv_parceiro' => 'Cnpj',
			'email_canal_pdv_parceiro' => 'E-mail',
            'codigo_canal_pdv_parceiro' => 'Codigo',
            'parceiro_canal_pdv_parceiro' => 'Parceiro'
        ];
    }
}
