<?php

namespace App\CarChecker\Controllers;

use App\CarChecker\Exceptions\MOTServiceException;
use App\CarChecker\Exceptions\VESServiceException;
use App\CarChecker\Services\MOTService;
use App\CarChecker\Services\VESService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function __construct(
        protected MOTService $motService,
        protected VESService $vesService
    ) {}

    public function lookup(Request $request)
    {
        $plate = $request->query('plate');
        $car = [
            'mot' => null,
            'ves' => null,
        ];
        $motHistory = null;
        $errorMessage = null;
        if ($plate) {
            try {
                $car['mot'] = [
                    'type' => 'data',
                    'data' => $this->motService->getHistoryForLicencePlate($plate),
                ];
            } catch (MOTServiceException $e) {
                
                $errorMessage = $e->getMessage();
                $apiDebug = $e->apiResponse; // optional: log or display for debugging
                // Example: log it
                Log::debug('MOT API Response', ['response' => $apiDebug]);
                $car['mot'] = [
                    'type' => 'error',
                    'error' => $apiDebug,
                ];
            }
            try {
                $car['ves'] = [
                    'type' => 'data',
                    'data' => $this->vesService->lookup($plate),
                ];
            } catch (VESServiceException $e) {
                $errorMessage = $e->getMessage();
                $apiDebug = $e->apiResponse; // optional: log or display for debugging
                // Example: log it
                Log::debug('VES API Response', ['response' => $apiDebug]);
                $car['ves'] = [
                    'type' => 'error',
                    'error' => $apiDebug,
                ];
            }
        }

        return view('check-car', compact('car'));
    }

    public function getVES(Request $request, string $plate)
    {
        return $this->vesService->lookup($plate);
    }

    public function getMOT(Request $request, string $plate)
    {
        return $this->motService->getHistoryForLicencePlate($plate);
    }
}
