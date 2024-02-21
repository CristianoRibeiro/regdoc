<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Storage;
use URL;
use GuzzleHttp;
use Exception;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

use App\Models\usuario_certificado;

class AssinaturaController extends Controller
{
	public function iniciar_lote(Request $request) {
		try {
			if ($request->session()->has('arquivos_'.$request->arquivos_token)) {
				$arquivos = $request->session()->get('arquivos_'.$request->arquivos_token);

				$arquivos_json = [];
				$restringir_assinante = false;
				$nome_assinante = null;
				$cpf_cnpj_assinante = null;
				if ($request->index_arquivo>=0) {
					$arquivo = $arquivos[$request->index_arquivo];
					$arquivo_path = $arquivo['no_local_arquivo'].'/'.$arquivo['no_arquivo'];
					$arquivo_content = Storage::get($arquivo_path);

					$arquivos_json[] = [
						'code' => $request->index_arquivo,
						'content' => base64_encode($arquivo_content),
						'filename' => $arquivo['no_descricao_arquivo'],
						'extension' => $arquivo['no_extensao'],
						'mime' => $arquivo['no_mime_type'],
						'hash' => $arquivo['no_hash'],
						'max_signers' => -1,
						'size' => $arquivo['nu_tamanho_kb']
					];

					$restringir_assinante = (isset($arquivo['restringir_assinante'])?$arquivo['restringir_assinante']:false);
					$nome_assinante = (isset($arquivo['nome_assinante'])?$arquivo['nome_assinante']:null);
					$cpf_cnpj_assinante = (isset($arquivo['cpf_cnpj_assinante'])?$arquivo['cpf_cnpj_assinante']:null);
				} else {
					foreach ($arquivos as $key => $arquivo) {
						if (in_array($arquivo['in_ass_digital'], ['S','O']) and $arquivo['in_assinado']=='N') {
							$arquivo_path = $arquivo['no_local_arquivo'].'/'.$arquivo['no_arquivo'];
							$arquivo_content = Storage::get($arquivo_path);

							$arquivos_json[] = [
								'code' => $key,
								'content' => base64_encode($arquivo_content),
								'filename' => $arquivo['no_descricao_arquivo'],
								'extension' => $arquivo['no_extensao'],
								'mime' => $arquivo['no_mime_type'],
								'hash' => $arquivo['no_hash'],
								'max_signers' => -1,
								'size' => $arquivo['nu_tamanho_kb']
							];

							$restringir_assinante = (isset($arquivo['restringir_assinante'])?$arquivo['restringir_assinante']:false);
							$nome_assinante = (isset($arquivo['nome_assinante'])?$arquivo['nome_assinante']:null);
							$cpf_cnpj_assinante = (isset($arquivo['cpf_cnpj_assinante'])?$arquivo['cpf_cnpj_assinante']:null);
						}
					}
				}

				$json = [
					'token' => env('PORTAL_ASSINATURAS_TOKEN'),
					'batch_files' => $arquivos_json,
					'options' => [
						'close_popup' => true,
				        'restrict_signers' => $restringir_assinante,
				        'url_origin' => URL::to('/')
					]
				];

				if ($nome_assinante and $cpf_cnpj_assinante) {
					$json['signers'][] = [
						'name' => $nome_assinante,
						'identifier' => $cpf_cnpj_assinante,
					];
				} else {
					$name = Auth::User()->no_usuario;
					$identifier = Auth::User()->pessoa->nu_cpf_cnpj;
					if ($identifier) {
						$json['signers'][] = [
							'name' => $name,
							'identifier' => $identifier,
						];
					}
				}

				$response_json = json_decode($this->enviar_lote($json), true);
				return response()->json($response_json);
			}
		} catch(ClientException $e) {
			echo $e->getResponse()->getBody(true);
		} catch(ConnectException $e) {
			echo 'Erro de conexão.';
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	public function retornar_lote(Request $request)
	{
		try {
			if (!empty($request->key) and !empty($request->arquivos_token) and !empty($request->index_arquivo)) {
				if ($request->session()->has('arquivos_'.$request->arquivos_token)) {
					$arquivos = $request->session()->get('arquivos_'.$request->arquivos_token);

					$detalhes_lote = json_decode($this->consultar_lote($request->key), true);

					$sucessos = [];
					$erros = [];
					if (isset($detalhes_lote['files'])) {
						foreach ($detalhes_lote['files'] as $key => $arquivo_lote) {
							$index_arquivo = $arquivo_lote['code'];
							$arquivo = $arquivos[$index_arquivo];

							if ($arquivo_lote['file_state_id']==3) {
								$nome_arquivo_assinado = Str::random(16).'.'.$arquivo['no_extensao'];
								$local_arquivo = $arquivo['no_local_arquivo'].'/'.$nome_arquivo_assinado;

								Storage::put($local_arquivo, base64_decode($arquivo_lote['last_signature']['content']));

								if (!Storage::exists($local_arquivo))
									throw new Exception('O arquivo não foi salvo corretamente.');

								$sucessos[] = $arquivo_lote['code'];

								array_push($arquivos[$index_arquivo]['no_arquivos_originais'], $arquivos[$index_arquivo]['no_arquivo']);
								$arquivos[$index_arquivo]['no_arquivo'] = $nome_arquivo_assinado;
								$arquivos[$index_arquivo]['in_assinado'] = 'S';
								$arquivos[$index_arquivo]['dt_assinado'] = Carbon::now();
								$arquivos[$index_arquivo]['no_hash'] = $arquivo_lote['last_signature']['hash'];
								$arquivos[$index_arquivo]['nu_tamanho_kb'] = $arquivo_lote['last_signature']['size'];

								// Certificado do assinante
								$certificado = $arquivo_lote['last_signature']['certificate'];

								$usuario_certificado = new usuario_certificado();
								$usuario_certificado = $usuario_certificado->where('nu_serial', $certificado['serial'])
																		   ->where('id_usuario', Auth::User()->id_usuario)
																		   ->first();
								if ($usuario_certificado) {
									$arquivos[$index_arquivo]['id_usuario_certificado'] = $usuario_certificado->id_usuario_certificado;
								} else {
									$args_certificado = [
				                        'no_comum' => $certificado['common_name'],
				                        'no_autoridade_raiz' => $certificado['organization_name'],
				                        'no_autoridade_unidade' => $certificado['organizational_unit'],
				                        'no_autoridade_certificadora' => $certificado['certificate_authority'],
				                        'nu_serial' => $certificado['serial'],
				                        'dt_validade_ini' => $certificado['start_at'],
				                        'dt_validade_fim' => $certificado['end_at'],
				                        'tp_certificado' => $certificado['version'],
				                        'nu_cpf_cnpj' => $certificado['identifier'],
				                        'no_responsavel' => $certificado['name'],
				                        'de_campos' => $certificado['fields']
				                    ];
				                    $novo_usuario_certificado = new usuario_certificado();

				                    $novo_usuario_certificado->insere($args_certificado);
									$arquivos[$index_arquivo]['id_usuario_certificado'] = $novo_usuario_certificado->id_usuario_certificado;
								}
							} else {
								$erros[] = $arquivo_lote['code'];
							}
						}
					}

					$request->session()->put('arquivos_'.$request->arquivos_token, $arquivos);

					$response_json = [
						'sucessos' => $sucessos,
						'erros' => $erros
					];
					return response()->json($response_json);
				}
			}
		} catch(ClientException $e) {
			echo $e->getResponse()->getBody(true);
		} catch(ConnectException $e) {
			echo 'Erro de conexão.';
		} catch(Exception $e) {
			echo $e->getMessage().'-'.$e->getLine();
		}
	}

	private function enviar_lote($json) {
		$URL = env('PORTAL_ASSINATURAS_URLBASE').'/api/batch';

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
        $request = $GuzzleClient->request('POST', $URL, [GuzzleHttp\RequestOptions::JSON => $json]);

        if ($request->getStatusCode()) {
            $body = $request->getBody();

            $retorno = '';
            while (!$body->eof()) {
                $retorno .= $body->read(1024);
            }

            return $retorno;
        } else {
            throw new Exception('O servidor retornou erro com o status '.$request->getStatusCode().'.');
        }
    }

	private function consultar_lote($key) {
        $URL = env('PORTAL_ASSINATURAS_URLBASE').'/api/batch/'.$key;

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
        $request = $GuzzleClient->request('GET', $URL);

        if ($request->getStatusCode()) {
			$stream = GuzzleHttp\Psr7\stream_for($request->getBody());

			return $stream;
        } else {
            throw new Exception('O servidor retornou erro com o status '.$request->getStatusCode().'.');
        }
    }
}
