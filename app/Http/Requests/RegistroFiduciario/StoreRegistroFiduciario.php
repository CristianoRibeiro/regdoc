<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

use stdClass;
use Session;
use Auth;
use Illuminate\Support\Str;

use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;

class StoreRegistroFiduciario extends FormRequest
{
    public function __construct(RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface)
    {
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
    }

    /**
     * Prepare fields for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->id_registro_fiduciario_tipo) {
            $args_tipos_partes = new StdClass();
            $args_tipos_partes->id_registro_fiduciario_tipo = $this->id_registro_fiduciario_tipo;
            $args_tipos_partes->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

            $filtros_tipos_partes = new stdClass();
            switch($this->tipo_insercao) {
                case 'P':
                    $filtros_tipos_partes->in_obrigatorio_proposta = 'S';
                    break;
                case 'C':
                    $filtros_tipos_partes->in_obrigatorio_contrato = 'S';
                    break;
            }
            
            $tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

            $this->merge([
                'tipos_partes' => $tipos_partes
            ]);

            // Partes na sessão
            $partes_merge = [];
            if (Session::has('partes_'.$this->registro_token)) {
                $partes = Session::get('partes_'.$this->registro_token);
                foreach ($partes as $key => $parte) {
                    $index = 'parte_' . $parte['id_tipo_parte_registro_fiduciario'];

                    if (isset($partes_merge[$index])) {
                        $partes_merge[$index]++;
                    } else {
                        $partes_merge[$index] = 1;
                    }
                }
            }

            $this->merge($partes_merge);
        }

        // Arquivos na sessão
        $arquivos_contrato = 0;
        if (Session::has('arquivos_'.$this->registro_token)) {
            $arquivos = Session::get('arquivos_'.$this->registro_token);
            foreach ($arquivos as $key => $arquivo) {
                if ($arquivo['id_tipo_arquivo_grupo_produto'] == config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'))
                    $arquivos_contrato++;
            }
        }

        $this->merge([
            'arquivos_contrato' => $arquivos_contrato
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
            // Produto
            'produto' => 'required|in:fiduciario,garantias',

            // Tipo de inserção
            'tipo_insercao' => 'required|in:P,C',

            // Tipo do registro fiduciário
            'id_registro_fiduciario_tipo' => 'required|exists:registro_fiduciario_tipo,id_registro_fiduciario_tipo',

            // Cartório de RI
            'id_pessoa_cartorio_ri' => 'nullable|exists:pessoa,id_pessoa',

            // Cartório de RTD
            'id_pessoa_cartorio_rtd' => 'nullable|exists:pessoa,id_pessoa',

            // Credor Fiduciário
            'id_registro_fiduciario_credor' => 'required_if:id_registro_fiduciario_tipo,1,2,3,6,7,8,9,10,12,14|exists:registro_fiduciario_credor,id_registro_fiduciario_credor',

            // Contrato 
            'nu_contrato' => 'nullable|required_if:tipo_insercao,C|string|max:30',
        ];

        if (count($this->tipos_partes ?? [])>0) {
            foreach ($this->tipos_partes as $tipo_parte) {
                if ($tipo_parte->in_construtora=='S') {
                    $regras += [
                        'id_construtora_'.$tipo_parte->id_tipo_parte_registro_fiduciario => 'required|exists:construtora,id_construtora'
                    ];
                } else {
                    $regras += [
                        'parte_'.$tipo_parte->id_tipo_parte_registro_fiduciario => 'integer|required|min:1'
                    ];
                }
            }
        }

        //Empreendimento
        if ($this->id_empreendimento == '-2') {
            $regras += [
                'no_empreendimento' => 'nullable|required_with:nu_unidade_empreendimento|string|max:255',
                'nu_unidade_empreendimento' => 'nullable|required_with:no_empreendimento|string|max:30',
            ];
        } elseif ($this->id_empreendimento == '-1') {
            $regras += [
                'no_empreendimento' => 'nullable|required_if:id_empreendimento,-1|string|max:255',
                'nu_unidade_empreendimento' => 'nullable|required|string|max:30',
            ];
        } else {
            $regras += [
                'id_empreendimento' => 'nullable|required_with:nu_unidade_empreendimento|exists:empreendimento,id_empreendimento',
                'nu_unidade_empreendimento' => 'nullable|required_with:id_empreendimento|string|max:30',
            ];
        }

        if ($this->tipo_insercao == 'C')
            $regras += [
                'arquivos_contrato' => 'integer|min:1',
            ];
        
        if ($this->tipo_insercao == 'P')
            $regras += [
                'nu_proposta' => 'nullable|required_without_all:id_empreendimento,no_empreendimento|string|max:30'
            ];

        return $regras;
    }

    public function attributes()
    {
        $attributes = [
            // Tipo de inserção
            'tipo_insercao' => 'Tipo de inserção',

            // Tipo do registro fiduciário
            'id_registro_fiduciario_tipo' => 'Tipo do registro',

            // Cartório de RI
            'id_pessoa_cartorio_ri' => 'Cartório de Registro de Imóveis',

            // Cartório de RTD
            'id_pessoa_cartorio_rtd' => 'Cartório de Registro de Títulos e Documentos',

            // Credor Fiduciário
            'id_registro_fiduciario_credor' => 'Credor fiduciário',

            // Custodiante
            'id_registro_fiduciario_custodiante' => 'Custodiante',

            // Contrato
            'nu_contrato' => 'Número do contrato',
            'nu_proposta' => 'Número da proposta',

            // Arquivos
            'arquivos_contrato' => 'Arquivo do contrato',

            // Empreendimento
            'id_empreendimento' => 'Empreendimento',
            'no_empreendimento' => 'Nome do empreendimento',
            'nu_unidade_empreendimento' => 'Unidade do empreendimento',
        ];

        // Partes
        if (count($this->tipos_partes ?? [])>0) {
            foreach ($this->tipos_partes as $tipo_parte) {
                $no_tipo_parte = Str::ucfirst(Str::lower($tipo_parte->no_registro_tipo_parte_tipo_pessoa));
                if ($tipo_parte->in_construtora=='S') {
                    $attributes += [
                        'id_construtora_'.$tipo_parte->id_tipo_parte_registro_fiduciario => $no_tipo_parte
                    ];
                } else {
                    $attributes += [
                        'parte_'.$tipo_parte->id_tipo_parte_registro_fiduciario => $no_tipo_parte
                    ];
                }
            }
        }

        return $attributes;
    }

    public function messages()
    {
        return [
            // Arquivos
            'arquivos_contrato.min' => 'O arquivo do contrato é obrigatório.'
        ];
    }
}
