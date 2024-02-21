<?php

return [
    'url' => env('CAPROXY_URL', 'https://proxy.api.prebanco.com.br'),
    'client_id' => env('CAPROXY_CLIENTID'),
    'expires_in' => env('CAPROXY_EXPIRES_IN', 3600),
    'jws' => [
        'algo' => env('CAPROXY_JWSALGO', 'RS256'),
        'keyfile' => env('CAPROXY_KEYFILE'),
    ],
    'request' => [
        'sign_algo' => ENV('CAPROXY_REQSIGNALGO', 'SHA256')
    ]
];
