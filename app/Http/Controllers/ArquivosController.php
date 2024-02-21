<?php

namespace App\Http\Controllers;

use App\Helpers\ValidHubPDFUtils;
use App\Http\Requests\InserirArquivo;
use App\Models\arquivo_grupo_produto;
use App\Models\tipo_arquivo_grupo_produto;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;
use PDF;
use Session;
use Storage;
use URL;
use Validator;

class ArquivosController extends Controller
{
	public function __construct() {}

	public function novo(Request $request)
	{
		$tipo_arquivo_grupo_produto = new tipo_arquivo_grupo_produto();
		$tipo_arquivo_grupo_produto = $tipo_arquivo_grupo_produto->find($request->id_tipo_arquivo_grupo_produto);

		// Argumentos para o retorno da view
		$compact_args = [
            'request' => $request,
			'in_converter_pdf' => $tipo_arquivo_grupo_produto->in_converter_pdf ?? 'S'
        ];


		if(in_array($request->id_tipo_arquivo_grupo_produto, [
            config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'),
            config('constants.TIPO_ARQUIVO.11.ID_OUTROS'),
            config('constants.TIPO_ARQUIVO.11.ID_PROCURACAO_CREDOR'),
            config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'),
            config('constants.TIPO_ARQUIVO.11.ID_FORMULARIO'),
        ])){
			return view('app.arquivos.arquivos-novo-multiplos', $compact_args);
		}

			return view('app.arquivos.arquivos-novo', $compact_args);
	}

	public function inserir(InserirArquivo $request)
	{
		try {
			$tipo_arquivo_grupo_produto = new tipo_arquivo_grupo_produto();
			$tipo_arquivo_grupo_produto = $tipo_arquivo_grupo_produto->find($request->id_tipo_arquivo_grupo_produto);

			if (!$request->session()->has('datapasta_'.$request->token)) {
				$request->session()->put('datapasta_'.$request->token,Carbon::now()->format('d-m-Y'));

			}
			$data_pasta = $request->session()->get('datapasta_'.$request->token);

			$destino = '/temp/'.$request->pasta.'/'.$data_pasta.'/'.$request->token.'/'.$request->id_tipo_arquivo_grupo_produto;
			$arquivo = $request->no_arquivo;
			$ext_arquivo = strtolower(Helper::extensao_arquivo($arquivo->getClientOriginalName()));
			$nome_arquivo = Str::random(12).'.'.$ext_arquivo;
			$no_descricao_arquivo = strtolower(Helper::remove_caracteres($arquivo->getClientOriginalName()));
			$nome_arquivos_originais = [];
			$converter_pdfa = false;

			if (Storage::putFileAs($destino, $arquivo, $nome_arquivo)) {
				$ext_conversao_arquivo = [
					'png','jpg','jpeg','bmp','doc','docx','rtf','xls','xlsx','ppt','pptx','pps','ppsx','txt'
				];
				if (!in_array($ext_arquivo, $ext_conversao_arquivo) && $ext_arquivo != 'pdf')
					throw new Exception('Extensão do arquivo não permitida');

				if (($tipo_arquivo_grupo_produto->in_converter_pdf ?? 'S')=='S') {
					if (in_array($ext_arquivo, $ext_conversao_arquivo)) {
						array_push($nome_arquivos_originais, $nome_arquivo);

						$arquivo_convertido = ValidHubPDFUtils::convertPDF($destino.'/'.$nome_arquivo, $ext_arquivo, true);
						$nome_arquivo = Str::random(12).'.pdf';
						$no_descricao_arquivo = Helper::substituir_extensao_arquivo($no_descricao_arquivo, 'pdf');

						Storage::put($destino . '/' . $nome_arquivo, $arquivo_convertido);
					} elseif ($ext_arquivo == 'pdf') {
						$content = Storage::get($destino.'/'.$nome_arquivo);
						if(Helper::verificar_assinatura_pdf($content) == false) {
							$arquivo_convertido = ValidHubPDFUtils::convertPDFA($destino . '/' . $nome_arquivo);

							Storage::move($destino . '/' . $nome_arquivo, $destino . '/original_' . $nome_arquivo);
							Storage::put($destino . '/' . $nome_arquivo, $arquivo_convertido);

							array_push($nome_arquivos_originais, 'original_' . $nome_arquivo);
						}
					}
				}

				if ($request->session()->has('arquivos_'.$request->token)) {
					$arquivos = $request->session()->get('arquivos_'.$request->token);
				} else {
					$arquivos = [];
				}

				if (isset($request->in_ass_digital)) {
					$in_ass_digital = $request->in_ass_digital;
				} else {
					$in_ass_digital = $tipo_arquivo_grupo_produto->in_ass_digital;
				}

				$arquivo = [
					'no_arquivo' => $nome_arquivo,
	                'no_descricao_arquivo' => $no_descricao_arquivo,
					'no_arquivos_originais' => $nome_arquivos_originais,
					'no_local_arquivo' => $destino,
					'in_ass_digital' => $in_ass_digital,
					'id_tipo_arquivo_grupo_produto' => $tipo_arquivo_grupo_produto->id_tipo_arquivo_grupo_produto,
					'in_assinado'=>'N',
					'dt_assinado'=>NULL,
					'id_usuario_certificado'=>NULL,
					'no_arquivo_p7s'=>NULL,
					'id_flex' => $request->id_flex,
					'texto' => $request->texto,
					'container' => $request->container,
					'no_extensao' => Helper::extensao_arquivo($nome_arquivo),
					'nu_tamanho_kb' => Storage::size($destino . '/' . $nome_arquivo),
					'no_mime_type' => Helper::mime_arquivo($destino . $nome_arquivo),
	            	'no_hash' => md5_file($arquivo->getRealPath())
				];

				$arquivos[$request->index_arquivos] = $arquivo;

				$request->session()->put('arquivos_'.$request->token,$arquivos);
			} else {
				throw new Exception('Não foi possível inserir o arquivo na pasta de destino.');
			}

            $response_json = [
                'message' => 'O arquivo foi inserido com sucesso.',
				'arquivo' => $arquivo,
				'token' => $request->token
            ];
            return response()->json($response_json);
		} catch (Exception $e) {
			$response_json = [
				'message' => $e->getMessage()
			];
			return response()->json($response_json, 500);
		}
	}

	public function remover(Request $request)
	{
		if ($request->session()->has('arquivos_'.$request->token)) {
			$arquivos = $request->session()->get('arquivos_'.$request->token);
			$arquivo = $arquivos[$request->index_arquivo];

			if($request->session()->has('files_'.$request->token)){
				$files = $request->session()->get('files_'.$request->token);
				unset($files[$request->index_arquivo]);
				$request->session()->put('files_'.$request->token, $files);
			}
	
			unset($arquivos[$request->index_arquivo]);
			$request->session()->put('arquivos_'.$request->token, $arquivos);

			$response_json = [
				'message' => 'O arquivo foi excluído com sucesso',
				'arquivo' => $arquivo,
				'token' => $request->token
			];
			return response()->json($response_json, 200);
		} else {
			$response_json = [
				'message'=> 'A sessão de arquivos não foi localizada.'
			];
			return response()->json($response_json, 400);
		}

			}

	public function visualizar_arquivo(Request $request)
	{
        if (!$request->session()->has('arquivos_tokens')) {
            $request->session()->put('arquivos_tokens', []);
        }

        $array_tokens = $request->session()->get('arquivos_tokens', []);

        $arquivo_grupo_produto = new arquivo_grupo_produto();
        $arquivo_grupo_produto = $arquivo_grupo_produto->find($request->id_arquivo_grupo_produto);

        if ($arquivo_grupo_produto) {
			$arquivo_token = Str::random(30);
			$array_tokens[$arquivo_token] = $arquivo_grupo_produto->id_arquivo_grupo_produto;

            $url_download = URL::to('/app/arquivos/download/'.$arquivo_token);
            $url_download_p7s = '';
            if (isset($arquivo_grupo_produto->no_arquivo_p7s)) {
                $url_download_p7s = URL::to('/app/arquivos/download/'.$arquivo_token.'/p7s');
            }

            $request->session()->put('arquivos_tokens', $array_tokens);

            // Argumentos para o retorno da view
			$compact_args = [
	            'arquivo_grupo_produto' => $arquivo_grupo_produto,
				'arquivo_token' => $arquivo_token
	        ];

			$response_json = [
				'message' => '',
				'view' => view('app.arquivos.arquivos-visualizar',$compact_args)->render(),
				'url_download' => $url_download,
				'url_download_p7s' => $url_download_p7s
			];
			return response()->json($response_json, 200);
		} else {
            $response_json = [
                'message' => 'O arquivo não foi encontrado.'
            ];
            return response()->json($response_json,400);
        }
	}

	public function render_arquivo(Request $request)
	{
		$array_tokens = $request->session()->get('arquivos_tokens', []);

        if (array_key_exists($request->arquivo_token, $array_tokens)) {
            $id_arquivo_grupo_produto = $array_tokens[$request->arquivo_token];

			$arquivo_grupo_produto = new arquivo_grupo_produto();
	        $arquivo_grupo_produto = $arquivo_grupo_produto->find($id_arquivo_grupo_produto);

	        if ($arquivo_grupo_produto) {
				$arquivo = Storage::get('/public/'.$arquivo_grupo_produto->no_local_arquivo.'/'.$arquivo_grupo_produto->no_arquivo);

                return response($arquivo, 200)
                    ->header('Content-Type', $arquivo_grupo_produto->no_mime_type)
                    ->header('Content-Disposition', 'inline; filename="'.$arquivo_grupo_produto->no_descricao_arquivo.'"');

			} else {
	            $response_json = [
	                'message' => 'O arquivo não foi encontrado.'
	            ];
	            return response()->json($response_json,400);
	        }
		} else {
			$response_json = [
				'message' => 'O token não foi encontrado.'
			];
			return response()->json($response_json,400);
		}
	}

	public function download_arquivo(Request $request)
	{
		$array_tokens = $request->session()->get('arquivos_tokens', []);

        if (array_key_exists($request->arquivo_token, $array_tokens)) {
            $id_arquivo_grupo_produto = $array_tokens[$request->arquivo_token];

			$arquivo_grupo_produto = new arquivo_grupo_produto();
	        $arquivo_grupo_produto = $arquivo_grupo_produto->find($id_arquivo_grupo_produto);
	        if ($arquivo_grupo_produto) {
				$arquivo_grupo_produto = $arquivo_grupo_produto->find($id_arquivo_grupo_produto);

				if ($arquivo_grupo_produto) {
					switch ($request->tipo) {
						case 'p7s':
							$no_arquivo = $arquivo_grupo_produto->no_arquivo_p7s;
							break;
						default:
							$no_arquivo = $arquivo_grupo_produto->no_arquivo;
							break;
					}

					$headers = [
						'Content-Type: ' . $arquivo_grupo_produto->no_mime_type
					];
					return Storage::download('/public/' . $arquivo_grupo_produto->no_local_arquivo . '/' . $no_arquivo, $arquivo_grupo_produto->no_descricao_arquivo, $headers);
				}
			} else {
	            $response_json = [
	                'message' => 'O arquivo não foi encontrado.'
	            ];
	            return response()->json($response_json,400);
	        }
		} else {
			$response_json = [
				'message' => 'O token não foi encontrado.'
			];
			return response()->json($response_json,400);
		}
	}

	public function assinaturas_arquivo(Request $request)
	{
        $arquivo_grupo_produto = new arquivo_grupo_produto();
        $arquivo_grupo_produto = $arquivo_grupo_produto->find($request->id_arquivo_grupo_produto);

        if ($arquivo_grupo_produto) {
            // Argumentos para o retorno da view
            $compact_args = [
                'arquivo_grupo_produto' => $arquivo_grupo_produto
            ];

            return view('app.assinatura.assinatura-assinaturas',$compact_args);
        } else {
            $response_json = [
                'message' => 'O arquivo não foi encontrado.'
            ];
            return response()->json($response_json,400);
        }
    }

	public function inserir_multiplos(Request $request)
	{
        $tipo_arquivo_grupo_produto = tipo_arquivo_grupo_produto::find($request->id_tipo_arquivo_grupo_produto);

        $files = $request->session()->has('files_'.$request->hash_files) ? $request->session()->get('files_'.$request->hash_files) : [];

		$datefolder = $request->session()->get('datefolder_'.$request->hash_files);

		$destino = 'temp/files/'.$datefolder.'/'.$request->hash_files;

        if ($request->session()->has('arquivos_'.$request->token)) {
            $arquivos = $request->session()->get('arquivos_'.$request->token);
        } else {
            $arquivos = [];
        }

		foreach($files as $key => $file){

			$arquivo = [
				'token' => $request->hash_files,
				'no_arquivo' => $file['no_arquivo'],
				'no_descricao_arquivo' => $file['no_descricao_arquivo'],
				'no_arquivos_originais' => $file['no_arquivos_originais'],
				'no_local_arquivo' => $destino,
				'in_ass_digital' => "N",
				'id_tipo_arquivo_grupo_produto' => $tipo_arquivo_grupo_produto->id_tipo_arquivo_grupo_produto,
				'in_assinado'=>'N',
				'dt_assinado'=>NULL,
				'id_usuario_certificado'=>NULL,
				'no_arquivo_p7s'=>NULL,
				'id_flex' => 0,
				'texto' => '',
				'container' => $request->container,
				'no_extensao' => Helper::extensao_arquivo($file['no_arquivo']),
				'nu_tamanho_kb' => Storage::size($destino . '/' . $file['no_arquivo']),
				'no_mime_type' => Helper::mime_arquivo($destino . $file['no_arquivo']),
				'no_hash' => $file['no_hash'],
				'hash_index' => $file['hash_index']
			];

			$arquivos[$key] = $arquivo;

		}
      
        $request->session()->put('arquivos_'.$request->token,$arquivos);

		$response_json = [
			'message' => 'O arquivos foi inserido com sucesso.',
			'arquivos' => $files,
			'token' => $request->token
		];
		return response()->json($response_json);
		
	}
}
