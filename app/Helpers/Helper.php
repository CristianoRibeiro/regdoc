<?php

namespace App\Helpers;

// Classes necessárias
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Storage;
use Carbon\Carbon;
use Exception;
use Mail;

use App\Models\pessoa;
use App\Models\pedido;

class Helper
{
    public static function in_menu_ativo(string $name, string $type = null)
    {
        switch ($type) {
            case 'url':
                return strstr(url()->current(), $name);
                break;
            case 'prefix':
                return strstr(request()->route()->getName(), $name);
                break;
            default:
                return request()->route()->named($name);
                break;
        }
    }
    public static function converte_float($valor)
    {
        if (!is_null($valor)) {
            $valor = preg_replace('/([^0-9\.,])/i', '', $valor);
            if (!is_numeric($valor)) {
                $valor = str_replace(array('.',','),array('','.'),$valor);
                return floatval($valor);
            } else {
                return floatval($valor);
            }
        } else {
            return NULL;
        }
    }
    public static function formatar_valor($value, $reais = true)
    {
        $value = (is_numeric($value)) ? $value : 0;
        if ($reais) {
            return 'R$ ' . number_format($value, 2, ',', '.');
        } else {
            return number_format($value, 2, ',', '.');
        }
    }
    public static function formata_data($value, $formato = 'd/m/Y')
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format($formato);
        } else {
            return '';
        }

    }
    public static function formata_hora($value, $formato = 'H:i:s')
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format($formato);
        } else {
            return '';
        }

    }
    public static function formata_data_hora($value, $formato = 'd/m/Y H:i:s')
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format($formato);
        } else {
            return '';
        }
    }
    public static function remove_caracteres($text)
    {
        $utf8 = [
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/&/' => 'e',
            '/\(/' => '',
            '/\)/' => '',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => '', // Literally a single quote
            '/[“”«»„]/u' => '', // Double quote
            '/ /' => '_', // nonbreaking space (equiv. to 0x160)
        ];
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
    public static function extensao_arquivo($no_arquivo)
    {
        $no_extensao = explode(".", $no_arquivo);
        return end($no_extensao);
    }
    public static function substituir_extensao_arquivo($no_descricao_arquivo, $nova_extensao)
    {
        $array_no_arquivo = explode('.', $no_descricao_arquivo);
        $array_no_arquivo[count($array_no_arquivo)-1] = $nova_extensao;

        return implode('.', $array_no_arquivo);
    }
    public static function array_telefone($telefone)
    {
        $telefone = preg_replace("/[^0-9]/", "", $telefone);

        $arr_telefone = [
            'nu_ddi' => '55',
            'nu_ddd' => substr($telefone, 0, 2),
            'nu_telefone' => substr($telefone, 2),
        ];

        return $arr_telefone;
    }
    public static function texto_template($texto,$array)
    {
        return str_replace(array_keys($array), array_values($array), $texto);
    }
    public static function somente_numeros($texto, $limite=-1)
    {
        if ($limite<0) {
            return preg_replace('#[^0-9]#','', trim($texto));
        } else {
            return preg_replace('#[^0-9]#','', self::cortar_string(trim($texto), $limite));
        }
    }
    public static function raw_arquivo($local_arquivo)
    {
        if (Storage::exists($local_arquivo)) {
            return Storage::get($local_arquivo);
        } else {
            return false;
        }
    }
    public static function mime_arquivo($local_arquivo)
    {
        if (Storage::exists($local_arquivo)) {
            return Storage::mimeType($local_arquivo);
        } else {
            return NULL;
        }
    }
    public static function pontuacao_cpf_cnpj($nu_cpf_cnpj)
    {
        if (strlen($nu_cpf_cnpj)>11) {
            return preg_replace('@^(\d{2,3})(\d{3})(\d{3})(\d{4})(\d{2})$@', '$1.$2.$3/$4-$5', $nu_cpf_cnpj);
        } else {
            return preg_replace('@^(\d{3})(\d{3})(\d{3})(\d{2})$@', '$1.$2.$3-$4', $nu_cpf_cnpj);
        }
    }

    /**
    * Gera o protocolo do pedido, em substituição a function do banco f_geraprotocolo
    * Formato: [MM][YY].[DD][ID_TIPO_PESSOA].[2ULTIMOSDIGITOS_IDPESSOA][2PRIMEIROSDIGITOS_PRODUTO].[CONTAGEM_POR_GRUPO_PRODUTO]
    *
    * @return string
    */
    public static function gerar_protocolo($id_pessoa, $id_produto, $id_grupo_produto)
    {
        $hoje = Carbon::now();

        $pessoa = new pessoa();
        $pessoa = $pessoa->find($id_pessoa);
        if ($pessoa) {
            $id_tipo_pessoa = str_pad($pessoa->id_tipo_pessoa, 2, STR_PAD_LEFT);
        } else {
            throw new Exception('Pessoa não encontrada.');
        }

        $total_pedidos = new pedido();
        $total_pedidos = $total_pedidos->join('produto', function($join) use ($id_grupo_produto) {
                                            $join->on('produto.id_produto', 'pedido.id_produto')
                                                 ->where('produto.id_grupo_produto', $id_grupo_produto);
                                       })
                                       ->where('pedido.dt_cadastro', '>=', $hoje->startOfDay())
                                       ->count();
        $total_pedidos = str_pad($total_pedidos+1, 4, '0', STR_PAD_LEFT);

        if (strlen($id_pessoa)>=2) {
            $id_pessoa_2ud = substr($id_pessoa, -2);
        } else {
            $id_pessoa_2ud = str_pad($id_pessoa, 2, '0', STR_PAD_LEFT);
        }

        if (strlen($id_produto)>=2) {
            $id_produto_2pd = substr($id_produto, 0, 2);
        } else {
            $id_produto_2pd = str_pad($id_produto, 2, '0', STR_PAD_LEFT);
        }

        return $hoje->format('m').$hoje->format('y').'.'.$hoje->format('d').$id_tipo_pessoa.'.'.$id_pessoa_2ud.$id_produto_2pd.'.'.$total_pedidos;
    }

    public static function to_fixed($valor, $casas=2)
    {
        if ($valor>0) {
            return sprintf("%.".$casas."f", $valor);
        } else {
            return '';
        }
    }

    public static function cortar_string($string, $tamanho)
    {
        $string = trim($string);
        if (strlen($string)>0) {
            return substr($string, 0, $tamanho);
        } else {
            return '';
        }
    }

    public static function get_filename_url($url)
    {
        $headers = get_headers($url, 1);
        $headers = array_change_key_case($headers, CASE_LOWER);

        preg_match('/filename=(.*)/', $headers['content-disposition'], $matches);

        if (!isset($matches[1])) {
            return false;
        }

        return $matches[1];
    }

    public static function download_arquivo_url($url)
    {
        $arquivo = file_get_contents($url);

        if ($arquivo) {
            $pasta = Str::random(10);
            $nome = self::get_filename_url($url);

            if (!$nome) {
                return false;
            }

            $destino_arquivo = 'temp/downloads/'.$pasta.'/'.$nome;
            Storage::put($destino_arquivo, $arquivo);

            return $destino_arquivo;
        }
        return false;
    }

    public static function var_from_url($var, $url)
    {
        parse_str(parse_url($url)['query'], $query_url);

        if (!isset($query_url[$var])) {
            return false;
        }

        return $query_url[$var];
    }

    /**
     * @param $tamanho
     * @return string
     */
    public static function gera_codigo_randomico ($tamanho)
    {
        $nums = implode('', range(0,9)); // 0123456789

        $codigo = '';

        for ($i = 0; $i < $tamanho; $i++) {
            $codigo .= rand(0, strlen($nums) - 1);
        }

        return $codigo; // ex.: "01545092"
    }

    /**
     * @param $valor
     * @return string|string[]|null
     */
    public static function limpar_mascara($valor)
    {
        if (!empty($valor)){
            $valor =  preg_replace('/\D+/', '', $valor);
        }

        return $valor;
    }

    public static function formatar_telefone($phone)
    {
        $telefone = preg_replace('/[^0-9]/', '', $phone);
        $array = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $telefone, $array);

        if ($array) {
            return '('.$array[1].') '.$array[2].'-'.$array[3];
        }

        return $telefone;
    }
    
    public static function mascarar($valor, $mascara)
    {
        $resultado = '';
        $k = 0;
        for($i = 0; $i<=strlen($mascara)-1; $i++) {
            if($mascara[$i] == '#') {
                if(isset($valor[$k]))
                    $resultado .= $valor[$k++];
            } else {
                if(isset($mascara[$i]))  
                    $resultado .= $mascara[$i];
            }
        }
        
        return $resultado;
    }

    public static function formatar_extenso($valor = 0, $reais = false) {
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

        $z = 0;
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $count = count($inteiro);
        $rt = "";

        for ($i = 0; $i < $count; $i++) {
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);

        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];

            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];

            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];

            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;

            $t = count($inteiro) - 1 - $i;

            $r .= $r ? ($reais ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : NULL) : "";

            if ($valor == "000") {
                $z++;
            } elseif ($z > 0) {
                $z--;
            }

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
                $r .= (($z > 1) ? " de" : "") . ($reais ? " ".$plural[$t] : NULL);
            }

            if ($r) {
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : "") . $r;
            }
        }

        return($rt ? $rt : "zero");
    }

    public static function numero_romano($numero, $minusculo = false)
    {
        $numero = intval($numero);
        $resultado = '';

        $array = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        foreach($array as $romano => $valor) {
            $divisao = intval($numero/$valor);
            $resultado .= str_repeat($romano, $divisao);

            $numero = $numero % $valor;
        }

        if ($minusculo) {
            $resultado = strtolower($resultado);
        }

        return $resultado;
    }

    public static function verificar_assinatura_pdf($content)
    {
        $signed = false;
        if (strpos($content, 'ETSI.CAdES.detached') !== false ||
            strpos($content, 'ETSI.PAdES.detached') !== false ||
            strpos($content, 'CAdES.detached') !== false ||
            strpos($content, 'PAdES.detached') !== false ||
            strpos($content, 'adbe.pkcs7.detached') !== false ||
            strpos($content, 'pkcs7.detached') !== false) {

            $signed = TRUE;
        }

        return $signed;
    }

    public static function format_kbytes($size)
    {
        $base = log($size)/log(1024);
        $suffix = array("KB", "MB", "GB", "TB")[floor($base)];
        return round(pow(1024, $base-floor($base))).' '.$suffix;
    }
}
