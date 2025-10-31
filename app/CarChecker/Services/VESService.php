<?php

namespace App\CarChecker\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class VESService
{
    public function lookup(string $registrationNumber): array
    {
        return Cache::remember("ves_lookup_{$registrationNumber}", 60 * 60 * 24, function () use ($registrationNumber) {
            $response = Http::withHeaders([
                'x-api-key' => config('ves.key'),
                'Content-Type' => 'application/json',
            ])->post(config('ves.url'), [
                'registrationNumber' => $registrationNumber,
            ]);

            // Throw exception on failure
            $response->throw();

            return $response->json();
        });
    }
}
