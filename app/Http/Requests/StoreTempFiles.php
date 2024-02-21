<?php

namespace App\Http\Requests;

use App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto;
use App\Helpers\Helper;

use Illuminate\Foundation\Http\FormRequest;

class StoreTempFiles extends FormRequest
{
    protected function prepareForValidation()
    {
        $tipo_arquivo_grupo_produto = tipo_arquivo_grupo_produto::where('id_tipo_arquivo_grupo_produto' , $this->id_tipo_arquivo_grupo_produto)->first();

        $this->merge([
            'nu_tamanho_max_kb' => $tipo_arquivo_grupo_produto->nu_tamanho_kb ?? config('app.max_upload')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => [
                'mimes:pdf,png,jpg,jpeg,bmp,doc,docx,rtf,xls,xlsx,ppt,pptx,pps,ppsx,txt',
                "max:{$this->nu_tamanho_max_kb}"
            ],
            'id_tipo_arquivo_grupo_produto' => 'required|numeric|exists:tipo_arquivo_grupo_produto',
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'O :attribute deve ter o tamanho maximo de ' . Helper::format_kbytes($this->nu_tamanho_max_kb) . '.',
            'file.mimes' => 'O campo :attribute deve ser um arquivo do tipo: pdf, png, jpg, jpeg, doc, docx, rtf, xls, xlsx, ppt, pptx, pps, ppsx, txt'
        ];
    }

    public function attributes()
    {
        return [
            'file' => 'arquivo'
        ];
    }
}
