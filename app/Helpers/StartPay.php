<?php

namespace App\Helpers;

use GuzzleHttp;

class StartPay
{

    public static function boleto() {

        $URL = config('startpay.url') . 'Boleto.php';

        $json = [
            'callback' => 'https://seuenderecodecallback.com.br',
            'nome' => 'Anhicolas Olsen',
            'cpf' => '86669109172',
            'celular' => '(65)98113-0531',
            'email' => 'anhicolas@hotmail.com',
            'valor' => '75.6295',
            'vencimento' => '7',
            'instrucao' => 'Não Receber após a data de vencimento',
            'endereco' => 'Rua 52',
            'bairro' => 'Boa Esperanca',
            'cidade' => 'Cuiabá',
            'numero' => '51',
            'uf' => 'MT',
            'cep' => '78.068-430',
        ];

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json' , 'Content-Type' => 'application/json', 'x-uid' => config('startpay.uid'),
            'token' => config('startpay.token')]]);
        $response = $GuzzleClient->request('POST', $URL, [GuzzleHttp\RequestOptions::JSON => $json]);

        print_r($response->getBody()->getContents());
    }

}