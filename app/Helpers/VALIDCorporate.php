<?php

namespace App\Helpers;

use Barryvdh\Debugbar\Facades\Debugbar;
use URL;
use GuzzleHttp;
use Exception;

class VALIDCorporate
{
    public static function criar_solicitacao($nome, $cpf_cnpj, $tp_pessoa, $email, $celular, $observacao, $preaprovado = false)
    {
        $token = self::login();

        if($preaprovado) {
            self::pre_aprovado($token, $nome, $cpf_cnpj, $email, $celular, $observacao);
        }

        return self::cliente_pre_aprovado($token, $nome, $cpf_cnpj, $tp_pessoa, $email, $celular, $observacao);
    }

    private static function login()
    {
        $URL = config('vcorporate.URL').'/api/v1/login';

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json' , 'Content-Type' => 'application/json']]);
        $response = $GuzzleClient->request('POST', $URL, [
            'form_params' => [
                'email' => config('vcorporate.LOGIN'),
                'password' => config('vcorporate.SENHA')
            ]
        ]);

        if ($response->getStatusCode()!=200)
            throw new Exception('O servidor retornou erro com o status '.$request->getStatusCode().'.');

        $response = $response->getBody()->getContents();
        $response = json_decode($response);

        if (!isset($response->success->token))
            throw new Exception('Erro ao obter o token.');

        return $response->success->token;

    }

    private static function pre_aprovado($token, $nome, $cpf_cnpj, $email, $celular, $observacao)
    {
        $URL = config('vcorporate.URL').'/api/v1/preaprovado/create';

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json' , 'Authorization' => 'Bearer ' . $token]]);
        $response = $GuzzleClient->request('POST', $URL, [
            'form_params' => [
                'empresa_id' => config('vcorporate.EMPRESA_ID'),
                'nome' =>  $nome,
                'cpf_cnpj' =>  $cpf_cnpj,
                'email' =>  $email,
                'celular' =>  $celular,
                'observacao' => $observacao
            ]
        ]);

        if ($response->getStatusCode()!=200)
            throw new Exception('O servidor retornou erro com o status '.$request->getStatusCode().'.');

        $response = $response->getBody()->getContents();

        return json_decode($response);

    }

    private static function cliente_pre_aprovado($token, $nome, $cpf_cnpj, $tp_pessoa, $email, $celular, $observacao)
    {
        $URL = config('vcorporate.URL').'/api/v1/clientepreaprovado/create';

        switch ($tp_pessoa) {
            case 'F':
                $sku = config('vcorporate.SKU_CPF');
                break;
            case 'J':
                $sku = config('vcorporate.SKU_CNPJ');
                break;
            default:
                throw new Exception('Tipo da pessoa não reconhecido.');
                break;
        }

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json' , 'Authorization' => 'Bearer ' . $token]]);
        $response = $GuzzleClient->request('POST', $URL, [
            'form_params' => [
                'tenant_url' => config('vcorporate.TENANT_URL'),
                'cliente' => config('vcorporate.CLIENTE'),
                'sku' => $sku,
                'nome' => $nome,
                'cpf_cnpj' => $cpf_cnpj,
                'email' => $email,
                'celular' => $celular,
                'observacao' => $observacao
            ]
        ]);

        if ($response->getStatusCode()!=200)
            throw new Exception('O servidor retornou erro com o status '.$request->getStatusCode().'.');

        $response = $response->getBody()->getContents();

        return json_decode($response);
    }
}
