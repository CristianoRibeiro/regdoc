<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class InserirPagamento extends FormRequest
{
    public function rules(): array
    {
        return [
            'in_isento' => 'required|in:S,N',
            'tipo_pagamento' => 'required|exists:registro_fiduciario_pagamento_tipo,id_registro_fiduciario_pagamento_tipo',
            'de_observacao' => 'required',
            'arquivos' => 'required|array|min:1',
            'arquivos.*.tipo' => 'required|in:41',
            'arquivos.*.nu_guia' => 'required_if:in_isento,N',
            'arquivos.*.nu_serie' => 'required_if:in_isento,N',
            'arquivos.*.no_emissor' => 'required_if:in_isento,N',
            'arquivos.*.va_guia' => 'required_if:in_isento,N',
            'arquivos.*.dt_vencimento' => 'required_if:in_isento,N|date_format:d/m/Y',
            'arquivos.*.nome' => 'required_if:in_isento,N|max:255',
            'arquivos.*.bytes' => 'required_if:in_isento,N',
            'arquivos.*.extensao' => 'required_if:in_isento,N|max:10',
            'arquivos.*.mime_type' => 'required_if:in_isento,N|max:100',
            'arquivos.*.hash' => 'required_if:in_isento,N|max:32'
        ];
    }

    public function attributes(): array
    {
        return [
            'in_isento' => 'Isenção de pagamento',
            'tipo_pagamento' => 'Tipo pagamento',
            'de_observacao' => 'Observação',
            'arquivos' => 'Arquivos',
            'arquivos.*.tipo' => 'Tipo arquivo',
            'arquivos.*.nu_guia' => 'Número da guia',
            'arquivos.*.nu_serie' => 'Número de série',
            'arquivos.*.no_emissor' => 'Emissor',
            'arquivos.*.va_guia' => 'Valor da guia',
            'arquivos.*.dt_vencimento' => 'Data de vencimento',
            'arquivos.*.nome' => 'Nome do Arquivo',
            'arquivos.*.bytes' => 'Bytes do Arquivo',
            'arquivos.*.extensao' => 'Extensão do Arquivo',
            'arquivos.*.mime_type' => 'Mime Type do Arquivo',
            'arquivos.*.hash' => 'Hash MD5 do Arquivo'
        ];
    }

    public function messages(): array
    {
        return [
            'arquivos.*.dt_vencimento.date_format' => 'O campo :attribute não confere com o formato dd/mm/yyyy.'
        ];
    }
}