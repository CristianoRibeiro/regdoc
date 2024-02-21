<?php

return [
    'url' => env('VSCORE_URL'),
    'username' => env('VSCORE_USERNAME'),
    'password' => env('VSCORE_PASSWORD'),
    'client_id' => env('VSCORE_CLIENT_ID'),
    'client_secret' => env('VSCORE_CLIENT_SECRET'),
    'contract' => env('VSCORE_CONTRACT'),
    'datavalid_contract' => env('VSCORE_DATAVALID_CONTRACT'),
    'sleep_api_time' => env('VSCORE_SLEEP_API_TIME', 3),
];
