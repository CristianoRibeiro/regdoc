<?php

namespace App\Helpers;

use URL;
use GuzzleHttp;

class VALIDTicket
{
    public static function status($ticket)
    {
        $token = self::login();

        return self::consultar_status($token, $ticket);
    }

    private static function login()
    {
        $URL = config('vticket.URL').'/ra-rest/api/auth/token';

        $json = [
            'user' => config('vticket.LOGIN'),
            'secret' => config('vticket.SENHA'),
        ];

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json' , 'Content-Type' => 'application/json']]);
        $response = $GuzzleClient->request('POST', $URL, [GuzzleHttp\RequestOptions::JSON => $json]);

        if ($response->getStatusCode()!=200)
            throw new Exception('O servidor retornou erro com o status '.$request->getStatusCode().'.');

        $response = $response->getBody()->getContents();
        $response = json_decode($response);

        if (!isset($response->token))
            throw new Exception('Erro ao obter o token.');

        return $response->token;

    }

    private static function consultar_status($token, $ticket)
    {
        $URL = config('vticket.URL').'/ra-rest/api/certificate-services/status';

        $json = [
            'ticket' => $ticket
        ];

        $GuzzleClient = new GuzzleHttp\Client(['headers' => ['Accept' => 'application/json' , 'Authorization' => 'Bearer ' . $token]]);
        $response = $GuzzleClient->request('POST', $URL, [GuzzleHttp\RequestOptions::JSON => $json]);

        if ($response->getStatusCode()!=200)
            throw new Exception('O servidor retornou erro com o status '.$request->getStatusCode().'.');

        $response = $response->getBody()->getContents();
        $response = json_decode($response);

        if (!isset($response->status))
            throw new Exception('Erro ao obter o token.');

        return $response;

    }
}
