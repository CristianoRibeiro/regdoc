<?php

namespace App\Helpers;

use App\Domain\AccessToken\Contracts\AccessTokenServiceInterface;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class CAProxy
{
    private static function encode(string $value): string
    {
        return base64_encode($value);
    }

    private static function clear(string $value)
    {
        return str_replace('=', '', strtr($value, '-_', '+/'));
    }

    /**
     * @throws Exception
     */
    public static function accessToken()
    {
        $accessTokenService = App::make(AccessTokenServiceInterface::class);
        $apiAccessToken = $accessTokenService->findByApi('caproxy');

        if ($apiAccessToken) {
            return $apiAccessToken->access_token;
        } else {
            $url = config('caproxy.url').'/auth/server/v1.1/token';
            $assertion = self::assertion();

            $fields = [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $assertion
            ];

            $response = Http::asForm()->post($url, $fields);

            if (!$response->successful()) {
                throw new Exception(
                    'O servidor retornou o status '.$response->status().' com o body '.$response->body().'.'
                );
            }

            $responseData = $response->json();

            if (!isset($responseData['access_token'])) {
                throw new Exception('Token não encontrado no body '.$response->json().'.');
            }

            $data = [
                'api' => 'caproxy',
                'type' => $responseData['token_type'],
                'access_token' => $responseData['access_token'],
                'expires_in' => Carbon::now()->addSeconds(config('caproxy.expires_in')),
                'used' => true,
                'date_last_use' => Carbon::now(),
                'url' => $url,
                'payload_send' => json_encode($fields),
                'payload_returned' => json_encode($responseData)
            ];

            $accessToken = $accessTokenService->create($data);
            return $accessToken->access_token;
        }
    }

    private static function assertion(): string
    {
        $jwt = self::header().'.'.self::payload();
        $jws = self::signature($jwt);

        return $jwt.'.'.$jws;
    }

    private static function header()
    {
        $headers = [
            "alg" => config('caproxy.jws.algo'), //"RS256"
            "typ" => "JWT"
        ];

        $result = self::encode(json_encode($headers, JSON_UNESCAPED_SLASHES));
        return self::clear($result);
    }

    private static function payload()
    {
        $nowTimestamp = Carbon::now()->timestamp;
        $nowTimestampMilliseconds = strval(Carbon::now()->getPreciseTimestamp(4));

        $payload = [
            "aud" => config('caproxy.url').'/auth/server/v1.1/token',
            "sub" => config('caproxy.client_id'),
            "iat" => strval($nowTimestamp),
            "exp" => $nowTimestamp + config('caproxy.expires_in'),
            "jti" => $nowTimestampMilliseconds,
            "ver" => "1.1"
        ];

        $result = self::encode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        return self::clear($result);
    }

    private static function signature($jwt)
    {
        $content = Storage::disk('local')->get(config('caproxy.jws.keyfile'));
        $privateKey = openssl_pkey_get_private($content);
        openssl_sign($jwt, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $result = self::encode($signature);
        return self::clear($result);
    }

    /**
     * @param  string  $url
     * @param $body
     * @param $accessToken
     * @return array
     */
    public static function signatureHeaders(string $url, $body, $accessToken): array
    {
        $nowRfc = Carbon::now()->toRfc3339String();
        $nowTimestamp = strval(Carbon::now()->getPreciseTimestamp(4));

        $urlParsed = parse_url($url);

        $request = "POST\n"; // Verb

        $request .= ($urlParsed['path'] ?? '')."\n"; // URI
        $request .= "\n"; // Params
        $request .= $body."\n"; // Body
        $request .= $accessToken."\n"; // Access Token
        $request .= $nowTimestamp."\n"; // Nonce
        $request .= $nowRfc."\n"; // Timestamp
        $request .= config('caproxy.request.sign_algo'); // Request Signature Algo

        return [
            'X-Brad-Signature' => self::signature($request),
            'X-Brad-Nonce' => $nowTimestamp,
            'X-Brad-Timestamp' => $nowRfc,
            'X-Brad-Algorithm' => config('caproxy.request.sign_algo')
        ];
    }

    /**
     * @throws Exception
     */
    public static function request(string $url, array $data)
    {
        $accessToken = self::accessToken();
        $headers = self::signatureHeaders($url, json_encode($data), $accessToken);

        return Http::withToken($accessToken)->withHeaders(
            [
                'access-token' => config('caproxy.client_id'),
                'X-Brad-Signature' => $headers['X-Brad-Signature'],
                'X-Brad-Nonce' => $headers['X-Brad-Nonce'],
                'X-Brad-Timestamp' => $headers['X-Brad-Timestamp'],
                'X-Brad-Algorithm' => $headers['X-Brad-Algorithm'],
            ]
        )->post($url, $data);
    }
}
