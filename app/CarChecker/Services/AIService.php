<?php

namespace App\CarChecker\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\CarChecker\Services\MOTService;
use App\CarChecker\Services\VESService;

class AIService
{
    private string $googleGeminiApiKey;

    public function __construct(
        private MOTService $motService,
        private VESService $vesService
    ) {
        $this->googleGeminiApiKey = env('GOOGLE_GEMINI_API_KEY');
    }

    public function generateText(string $licensePlate): string
    {
        return Cache::remember("ai_review_{$licensePlate}", 60 * 60 * 24, function () use ($licensePlate) {

            $payload = [
                "system_instruction" => [
                    "parts" => [
                        [
                            "text" => "You are a Car checker AI assistant that helps review car information and provide accurate responses based on the data available."
                        ],
                        [
                            "text" => "You will be provided with a car licence plate, MOT history, and vehicle details."
                        ],
                        [
                            "text" => "Use this information to answer 'Is this a good car to buy?' accurately and concisely."
                        ],
                        [
                            "text" => "Reply should either be 'No, because <reasons>', or 'Yes, based on MOT data, it is worth no more than £<amount>'."
                        ],
                        [
                            "text" => "Try to avoid saying no and instead add 'Expect to pay £<amount> for repairs' if there are problems below £1000."
                        ],
                        [
                            "text" => "Say no if there are serious issues in the MOT history or vehicle details or if the vehicle has less than 6 months MOT remaining."
                        ],
                    ]
                ],
                "contents" => [
                    [
                        "parts" => [
                            [
                                "text" => "License Plate: $licensePlate"
                            ],
                            [
                                "text" => "MOT History: " . json_encode($this->motService->getHistoryForLicencePlate($licensePlate))
                            ],
                            [
                                "text" => "Vehicle Details: " . json_encode($this->vesService->lookup($licensePlate))
                            ],
                        ]
                    ]
                ],
            ];

            $response = Http::withHeaders([
                'x-goog-api-key' => $this->googleGeminiApiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent', $payload);
            logger()->debug(__METHOD__, ['payload' => $payload, 'response' => $response->json()]);

            return $response->json()['candidates'][0]['content']['parts'][0]['text'];
        });
    }
}
