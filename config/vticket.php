<?php

return [
    'URL' => env('VTICKET_URL'),
    'LOGIN' => env('VTICKET_LOGIN'),
    'SENHA' => env('VTICKET_SENHA'),
    'CRON_ATUALIZACAO_TICKET' => env('VTICKET_CRON_ATUALIZACAO_TICKET', '*/30 6-22 * * 1-5')
];