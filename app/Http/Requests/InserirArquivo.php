<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto;

use App\Helpers\Helper;

class InserirArquivo extends FormRequest
{

     /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $tipo_arquivo_grupo_produto = tipo_arquivo_grupo_produto::where('id_tipo_arquivo_grupo_produto' , $this->id_tipo_arquivo_grupo_produto)->first();

        $this->merge([
            'nu_tamanho_max' => $tipo_arquivo_grupo_produto->nu_tamanho_kb ?? config('app.max_upload')
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
        return [
            'no_arquivo' => "required|file|max:".$this->nu_tamanho_max,
			'token' => 'required|alpha_num',
			'id_tipo_arquivo_grupo_produto' => 'required|numeric|exists:tipo_arquivo_grupo_produto',
			'id_flex' => 'nullable|numeric',
			'texto' => 'nullable|string',
			'pasta' => 'nullable|string',
			'container' => 'required|string',
			'index_arquivos' => 'required|numeric',
			'in_ass_digital' => 'nullable|string|in:N,S,O',
            'in_ass_digital' => 'nullable|string|in:N,S,O'
        ];
    }

    public function messages()
    {
        return [
            'no_arquivo.required' => 'O arquivo é obrigatório.',
            'no_arquivo.max' => "O tamanho do 'arquivo' não deve ter mais que ".Helper::format_kbytes($this->nu_tamanho_max)
        ];
    }
}
