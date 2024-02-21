<?php

return [
    'BANCOS' => [
        'BRADESCO_AGRO' => [
            'local' => 4,
            'development' => 1760,
            'staging' => 1760,
            'production' => 76450
        ][env('APP_ENV')]
    ]
];