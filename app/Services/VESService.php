<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VESService
{
    public function lookup(string $registrationNumber): array
    {
        return json_decode('{"registrationNumber":"PY06FTD","taxStatus":"Taxed","taxDueDate":"2025-11-01","motStatus":"Valid","make":"AUDI","yearOfManufacture":2006,"engineCapacity":1986,"co2Emissions":169,"fuelType":"DIESEL","markedForExport":false,"colour":"BLACK","typeApproval":"M1","dateOfLastV5CIssued":"2024-11-01","motExpiryDate":"2026-05-09","wheelplan":"2 AXLE RIGID BODY","monthOfFirstRegistration":"2006-05"}', true);
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
