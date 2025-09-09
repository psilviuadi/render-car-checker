<?php

namespace App\Services;

use App\Exceptions\MOTServiceException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MOTService
{
    protected string $tokenUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $scope;
    protected string $apiKey;
    protected string $endpoint;

    public function __construct()
    {
        $this->tokenUrl     = config('mot.token_url');
        $this->clientId     = config('mot.client_id');
        $this->clientSecret = config('mot.client_secret');
        $this->scope        = config('mot.scope');
        $this->apiKey       = config('mot.api_key');
        $this->endpoint     = config('mot.endpoint');
    }

    /**
     * Get MOT history for a given licence plate
     */
    public function getHistoryForLicencePlate(string $licence): array
    {
        $licence = strtoupper(trim($licence));

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            throw new MOTServiceException('Unable to get access token.');
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'X-API-Key'     => $this->apiKey,
        ])->get($this->endpoint . $licence);

        $responseBody = $response->json();

        if (!$response->ok()) {
            Log::error(__METHOD__ . " - response is not ok", $responseBody);
            throw new MOTServiceException(
                'Failed to retrieve MOT data from the API.',
                $responseBody
            );
        }
        logger()->debug(__METHOD__, $responseBody);

        return $responseBody;
    }

    /**
     * Retrieve an OAuth2 access token, with caching
     */
    protected function getAccessToken(): ?string
    {
        return Cache::remember('mot_api_access_token', 1100, function () {
            $response = Http::asForm()->post($this->tokenUrl, [
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope'         => $this->scope,
            ]);
            if (!$response->ok()) {
                Log::error(__METHOD__ . " - response is not ok", $response->json());
                return null;
            }
            Log::debug(__METHOD__, $response->json());
            return $response->json()['access_token'] ?? null;
        });
    }
}
