<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\LogDB;
use App\Helpers\ValidHubPDFUtils;

use App\Http\Requests\StoreTempFiles;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Ramsey\Uuid\Uuid;

use Exception;

class TempFilesController extends Controller
{
    public function store(StoreTempFiles $request)
    {
        try {
            $files = [];
            if ($request->session()->has('files_'.$request->hash_files)) {
                $files = $request->session()->get('files_'.$request->hash_files);
            }else{
                $request->session()->put('files_'.$request->hash_files, Carbon::now()->format('Y-m-d'));
            }
            if (!$request->session()->has('datefolder_'.$request->hash_files)) {
    			$request->session()->put('datefolder_'.$request->hash_files, Carbon::now()->format('Y-m-d'));
    		}
    		$datefolder = $request->session()->get('datefolder_'.$request->hash_files);

            $temp_folder = 'temp/files/'.$datefolder.'/'.$request->hash_files;


            $file = $request->file;
            $uuid = Uuid::uuid4()->toString();
            $hash_index = Str::random(12);
            $filename = $file->getClientOriginalName();
            $filepath = Storage::putFileAs($temp_folder, $file, $filename);
            $nome_arquivos_originais = [];
            $response_json = [];

            if (!$filepath)
                throw new Exception('Erro ao salvar o arquivo');

            if ($file->extension() != 'pdf'){

                // cria um novo arquivo convertido
                $arquivo = ValidHubPDFUtils::convertPDF($temp_folder . '/' . $filename, $file->extension(), true);
                $nome_arquivo = Helper::substituir_extensao_arquivo($filename, 'pdf');
                $extensao_arquivo = Helper::extensao_arquivo($nome_arquivo);
                Storage::put($temp_folder . '/' . $nome_arquivo, $arquivo);
                $caminho_arquivo = $temp_folder . '/' . $nome_arquivo;
                $mime_type = Storage::mimeType($caminho_arquivo);

                $files[$hash_index] = [
                    'uuid' => $uuid,
                    'filename' => $nome_arquivo,
                    'temp_folder' => $temp_folder,
                    'extension' => $extensao_arquivo,
                    'mime' => $mime_type,
                    'no_arquivo' => $nome_arquivo,
                    'no_descricao_arquivo' => $nome_arquivo,
                    'id_tipo_arquivo_grupo_produto' => $request->id_tipo_arquivo_grupo_produto,
                    'no_local_arquivo' => $temp_folder,
                    'no_extensao' => $extensao_arquivo,
                    'in_ass_digital' => $request->in_assinado,
                    'nu_tamanho_kb' => Storage::size($caminho_arquivo),
                    'no_hash' => hash_file('md5', $file->getRealPath()),
                    'no_mime_type' => $mime_type,
                    'no_arquivos_originais' => $nome_arquivos_originais,
                    'token' => $request->hash_files,
                    'hash_index' => $hash_index
                ];

                $request->session()->put('files_'.$request->hash_files, $files);

                $response_json = [
                    'request' => $request,
                    'in_converter_pdf' => $request->in_converter_pdf,
                    'status' => 'success',
                    'message' => 'O arquivo foi inserido com sucesso.',
                    'hash_index' => $hash_index
                ];

                return response()->json($response_json);

            }
            $files[$hash_index] = [
                'uuid' => $uuid,
                'filename' => $filename,
                'temp_folder' => $temp_folder,
                'extension' => $file->extension(),
                'mime' => $file->getMimeType(),
                'no_arquivo' => $filename,
                'no_descricao_arquivo' => $filename,
                'id_tipo_arquivo_grupo_produto' => $request->id_tipo_arquivo_grupo_produto,
                'no_local_arquivo' => $temp_folder,
                'no_extensao' => $file->extension(),
                'in_ass_digital' => $request->in_assinado,
                'nu_tamanho_kb' => Storage::size($temp_folder . '/' . $filename),
                'no_hash' => hash_file('md5', $file->getRealPath()),
                'no_mime_type' => $file->getMimeType(),
                'no_arquivos_originais' => $nome_arquivos_originais,
                'token' => $request->hash_files,
                'hash_index' => $hash_index
            ];

            $request->session()->put('files_'.$request->hash_files, $files);

            $response_json = [
                'request' => $request,
                'in_converter_pdf' => $request->in_converter_pdf,
                'status' => 'success',
                'message' => 'O arquivo foi inserido com sucesso.',
                'hash_index' => $hash_index
            ];
         
            return response()->json($response_json);

        } catch(Exception $e) {
            $error_message = $e->getMessage().' - Arquivo: '.$e->getFile().' - Linha: '.$e->getLine();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                $error_message,
                'Upload Multiplos Arquivos',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'error',
                'message' => 'Erro desconhecido, tente novamente mais tarde.'.(config('app.env')!='production'?$error_message:'')
            ];
            return response()->json($response_json, 500);
        }
    }

    /**
     * Remove a temporary file
     *
     * @return void
     */
    public function destroy(Request $request)
    {
        try {
            $success_response = [
                'status' => 'success',
				'message' => 'O arquivo foi removido com sucesso.'
			];
            if (!$request->session()->has('files_'.$request->hash_files)) return response()->json($success_response, 204);

			$files = $request->session()->get('files_'.$request->hash_files);

            if(!isset($files[$request->temp_file])) return response()->json($success_response, 204);
            $file = $files[$request->temp_file];

            Storage::delete($file['temp_folder'].$file['uuid']);

			unset($files[$request->temp_file]);

			$request->session()->put('files_'.$request->hash_files, $files);

			return response()->json($success_response, 200);
        } catch(Exception $e) {
            $error_message = $e->getMessage().' - Arquivo: '.$e->getFile().' - Linha: '.$e->getLine();

            $response_json = [
                'status' => 'error',
                'message' => 'Erro desconhecido, tente novamente mais tarde.'.(config('app.env')!='production'?$error_message:'')
            ];
            return response()->json($response_json, 500);
        }
    }
}
