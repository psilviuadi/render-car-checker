<?php

namespace App\CarChecker\Services;

use Illuminate\Support\Facades\Http;

class VESService
{
    public function lookup(string $registrationNumber): array
    {
        $response = Http::withHeaders([
            'x-api-key' => config('ves.key'),
            'Content-Type' => 'application/json',
        ])->post(config('ves.url'), [
            'registrationNumber' => $registrationNumber,
        ]);

        // Throw exception on failure
        $response->throw();
        logger()->debug(__METHOD__, $response->json());
        return $response->json();
    }
}
