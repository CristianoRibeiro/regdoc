<?php

namespace App\Helpers;

use Exception;
use Storage;

use Illuminate\Support\Str;

use App\Models\arquivo_grupo_produto;
use App\Models\arquivo_grupo_produto_assinatura;
use App\Models\arquivo_grupo_produto_composicao;
use App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto;
use App\Exceptions\RegdocException;

class Upload
{
    public static function copiar_arquivo($origem_arquivo, $destino_arquivo)
    {
    	if (!Storage::exists($origem_arquivo))
            throw new Exception('O arquivo original não foi encontrado na origem.');

        if (Storage::exists($destino_arquivo))
            Storage::delete($destino_arquivo);

        if (!Storage::copy($origem_arquivo, $destino_arquivo))
            throw new Exception('Erro ao copiar o arquivo original para o destino.');

        return true;
    }

    public static function insere_arquivo($arquivo, $id_grupo_produto, $destino='')
    {
        $origem_arquivo = $arquivo['no_local_arquivo'].'/'.$arquivo['no_arquivo'];
        $destino_arquivo = '/public'.$destino.'/'.$arquivo['no_arquivo'];

        $args_novo_arquivo = [
            'id_grupo_produto' => $id_grupo_produto,
            'id_tipo_arquivo_grupo_produto' => $arquivo['id_tipo_arquivo_grupo_produto'],
            'no_arquivo' => $arquivo['no_arquivo'],
            'no_descricao_arquivo' => $arquivo['no_descricao_arquivo'],
            'no_local_arquivo' => $destino,
            'no_extensao' => $arquivo['no_extensao'],
            'in_ass_digital' => $arquivo['in_assinado'] ?? 'N',
            'nu_tamanho_kb' => $arquivo['nu_tamanho_kb'],
            'no_hash' => $arquivo['no_hash'],
            'dt_ass_digital' => $arquivo['dt_assinado'] ?? NULL,
            'id_usuario_certificado' => $arquivo['id_usuario_certificado'] ?? NULL,
            'no_arquivo_p7s' => (isset($arquivo['no_arquivo_p7s'])?$arquivo['no_arquivo_p7s']:NULL),
            'no_hash_p7s' => (isset($arquivo['no_hash_p7s'])?$arquivo['no_hash_p7s']:NULL),
            'no_mime_type' => Helper::mime_arquivo($origem_arquivo),
            'no_url_origem' => (isset($arquivo['no_url_origem'])?$arquivo['no_url_origem']:NULL),
        ];

        $novo_arquivo_grupo_produto = new arquivo_grupo_produto();
        if (!$novo_arquivo_grupo_produto->insere($args_novo_arquivo))
            throw new Exception('Erro ao inserir o arquivo no banco de dados.');

        if ($args_novo_arquivo['id_usuario_certificado']) {
            $args_novo_arquivo['id_arquivo_grupo_produto'] = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;
            $novo_arquivo_grupo_produto_assinatura = new arquivo_grupo_produto_assinatura();
            if (!$novo_arquivo_grupo_produto_assinatura->insere($args_novo_arquivo))
                throw new Exception('Erro ao inserir a assinatura no banco de dados.');
        }

        Upload::copiar_arquivo($origem_arquivo, $destino_arquivo);

        if (isset($arquivo['no_hash_p7s'])>0) {
            $origem_arquivo_p7s = $arquivo['no_local_arquivo'].'/'.$arquivo['no_arquivo_p7s'];
            $destino_arquivo_p7s = '/public'.$destino.'/'.$arquivo['no_arquivo_p7s'];
            Upload::copiar_arquivo($origem_arquivo_p7s, $destino_arquivo_p7s);
        }

        if (count($arquivo['no_arquivos_originais'])>0) {
            foreach ($arquivo['no_arquivos_originais'] as $no_arquivo_original) {
                $origem_arquivo_original = $arquivo['no_local_arquivo'].'/'.$no_arquivo_original;
                $destino_arquivo_original = '/public'.$destino.'/'.$no_arquivo_original;

                $args_novo_arquivo_original = [
                    'id_arquivo_grupo_produto' => $novo_arquivo_grupo_produto->id_arquivo_grupo_produto,
                    'no_arquivo' => $no_arquivo_original,
                    'no_local_arquivo' => $destino,
                    'no_extensao' => Helper::extensao_arquivo($no_arquivo_original),
                    'nu_tamanho_kb' => Storage::size($arquivo['no_local_arquivo'].'/'.$no_arquivo_original),
                    'no_hash' => hash('md5', Helper::raw_arquivo($origem_arquivo))
                ];

                $novo_arquivo_grupo_produto_composicao = new arquivo_grupo_produto_composicao();
                if (!$novo_arquivo_grupo_produto_composicao->insere($args_novo_arquivo_original))
                    throw new Exception('Erro ao inserir o arquivo original no banco de dados.');

                Upload::copiar_arquivo($origem_arquivo_original, $destino_arquivo_original);
            }
        }

        return $novo_arquivo_grupo_produto;
    }

    public static function insere_arquivo_api($arquivo, $id_grupo_produto, $destino, $id_tipo_arquivo_grupo_produto = NULL)
    {
        if (!$id_tipo_arquivo_grupo_produto) {
            $tipo_arquivo = new tipo_arquivo_grupo_produto();
            $tipo_arquivo = $tipo_arquivo->where('co_tipo_arquivo', $arquivo['tipo'])->first();

            $id_tipo_arquivo_grupo_produto = $tipo_arquivo->id_tipo_arquivo_grupo_produto;
        }

        $nome_arquivo = Str::random(12).'.'.$arquivo['extensao'];
        $destino_arquivo = '/public'.$destino.'/'.$nome_arquivo;

        if (!empty($arquivo['bytes'])) {
            $bytes = base64_decode($arquivo['bytes']);
        } elseif (!empty($arquivo['url'])) {
            $bytes = file_get_contents($arquivo['url']);
            if (!$bytes) {
                throw new RegdocException('A URL do arquivo não está disponível para download.');
            }
        }

        if (!Storage::put($destino_arquivo, $bytes))
            throw new Exception('Erro ao salvar o arquivo.');

        $args_novo_arquivo = [
            'id_grupo_produto' => $id_grupo_produto,
            'id_tipo_arquivo_grupo_produto' => $id_tipo_arquivo_grupo_produto,
            'no_arquivo' => $nome_arquivo,
            'no_descricao_arquivo' => $arquivo['nome'],
            'no_local_arquivo' => $destino,
            'no_extensao' => $arquivo['extensao'],
            'nu_tamanho_kb' => Storage::size($destino_arquivo),
            'no_hash' => $arquivo['hash'],
            'no_mime_type' => $arquivo['mime_type'],
            'no_url_origem' => $arquivo['url'] ?? null,
        ];

        $novo_arquivo_grupo_produto = new arquivo_grupo_produto();
        if ($novo_arquivo_grupo_produto->insere($args_novo_arquivo)) {
            return $novo_arquivo_grupo_produto;
        } else {
            throw new Exception('Erro ao inserir o arquivo no banco de dados.');
        }
    }
}
