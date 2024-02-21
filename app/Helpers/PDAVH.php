<?php

namespace App\Helpers;

use URL;
use Exception;
use Illuminate\Support\Facades\Http;

class PDAVH
{
    public static function init_signature_process($title, $id_pedido, $type, $files, $signers, ?string $url_notificacao = null)
    {
        $token = self::generate_token();

        $URL = config('pdavh.URL').'/api/signature-processes';
        
        $response = Http::withToken($token->access_token)->withHeaders(['Accept' => 'application/json'])->post($URL, [
            'title' => $title,
            'code' => $id_pedido,
            'type' => $type,
            'process_files' => $files,
            'signers' => $signers,
            'options' => [
                'due_date' => null,
                'period_notify' => null,
                'url_notification' => $url_notificacao ?? URL::to('/pdavh/notificacao'),
                'url_origin' => URL::to('/protocolo'),
                'auto_init' => true,
                'send_init_emails' => false
            ]
        ]);

        if (!$response->successful())
            throw new Exception('O servidor retornou erro com o status '.$response->status().' com o body '.$response->body().'.');

        return json_decode($response->body());
    }

    public static function show_signature_process($uuid_process)
    {
        $token = self::generate_token();

        $URL = config('pdavh.URL').'/api/signature-processes/'.$uuid_process;

        $response = Http::withToken($token->access_token)->get($URL);

        if (!$response->successful())
            throw new Exception('O servidor retornou erro com o status '.$response->status().' com o body '.$response->body().'.');

        return json_decode($response->body());
    }

    public static function get_file_signature($uuid_process, $uuid_file, $uuid_signer)
    {
        $token = self::generate_token();

        $URL = config('pdavh.URL').'/api/signature-processes/'.$uuid_process.'/files/'.$uuid_file.'/signatures/'.$uuid_signer;

        $response = Http::withToken($token->access_token)->get($URL);

        if (!$response->successful())
            throw new Exception('O servidor retornou erro com o status '.$response->status().' com o body '.$response->body().'.');

        return json_decode($response->body());
    }

    public static function cancel_signature_process($uuid_process)
    {
        $token = self::generate_token();

        $URL = config('pdavh.URL').'/api/signature-processes/'.$uuid_process;

        $response = Http::withToken($token->access_token)->delete($URL);

        if (!$response->successful())
            throw new Exception('O servidor retornou erro com o status '.$response->status().' com o body '.$response->body().'.');

        return json_decode($response->body());
    }

    private static function generate_token()
    {
        $URL = config('pdavh.URL').'/api/auth';

        $response = Http::withHeaders(['Accept' => 'application/json'])->post($URL, [
            'username' => config('pdavh.USERNAME'),
            'password' => config('pdavh.PASSWORD')
        ]);

        if (!$response->successful())
            throw new Exception('O servidor retornou erro com o status '.$response->status().' com o body '.$response->body().'.');

        $response_json = json_decode($response->body());

        if (!isset($response_json->access_token))
            throw new Exception('O servidor não retornou o token de acesso.');

        return $response_json;
    }
}
