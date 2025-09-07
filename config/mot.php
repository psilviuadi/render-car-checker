<?php

return [
    'token_url' => env('MOT_TOKEN_URL'),
    'client_id' => env('MOT_CLIENT_ID'),
    'client_secret' => env('MOT_CLIENT_SECRET'),
    'scope' => env('MOT_SCOPE', 'https://tapi.dvsa.gov.uk/.default'),
    'api_key' => env('MOT_API_KEY'),
    'endpoint' => env('MOT_ENDPOINT', 'https://history.mot.api.gov.uk/v1/trades/vehicles/registration/'),
];
