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
        $str = '{"registration":"PY06FTD","make":"AUDI","model":"A4","firstUsedDate":"2006-05-31","fuelType":"Diesel","primaryColour":"Black","registrationDate":"2006-05-31","manufactureDate":"2006-05-31","engineSize":"1968","hasOutstandingRecall":"No","motTests":[{"registrationAtTimeOfTest":null,"motTestNumber":"859189994891","completedDate":"2025-04-22T12:57:38.000Z","expiryDate":"2026-05-09","odometerValue":"181892","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Front Headlamp lens slightly defective slight faded. (4.1.1 (b) (i))","type":"MINOR"},{"dangerous":false,"text":"Oil leak, but not excessive (8.4.1 (a) (i))","type":"ADVISORY"},{"dangerous":false,"text":"General corrosion / off side front bumper slight insecure.","type":"ADVISORY"},{"dangerous":false,"text":"Front Suspension arm pin or bush worn but not resulting in excessive movement both side upper front/front and front rear arms bushes slight worn. (5.3.4 (a) (i))","type":"ADVISORY"},{"dangerous":false,"text":"Nearside Front Shock absorbers has light misting of oil (5.3.2 (b))","type":"ADVISORY"},{"dangerous":false,"text":"Rear Shock absorbers has slight external damage to the casing  corroded. (5.3.2 (b))","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"871448493969","completedDate":"2024-04-15T11:38:19.000Z","expiryDate":"2025-05-09","odometerValue":"178281","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Undertrays fitted.","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"365091906432","completedDate":"2024-04-15T07:59:43.000Z","expiryDate":null,"odometerValue":"178281","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Stop lamp(s) not working (4.3.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Rear Direction indicator not working (4.4.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp aim projected beam image is obviously incorrect (4.1.2 (c))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp aim unable to be tested No beam pattern. (4.1.2 (b))","type":"MAJOR"},{"dangerous":false,"text":"Undertrays fitted.","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"443303162590","completedDate":"2023-05-10T14:23:53.000Z","expiryDate":"2024-05-09","odometerValue":"176436","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"535287868660","completedDate":"2023-05-09T10:17:18.000Z","expiryDate":null,"odometerValue":"176435","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Rear Registration plate inscription illegible (0.1 (b))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp not working on dipped beam (4.1.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp aim unable to be tested (4.1.2 (b))","type":"MAJOR"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"331974589535","completedDate":"2022-06-28T15:56:01.000Z","expiryDate":"2023-06-27","odometerValue":"171469","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"122215436389","completedDate":"2021-03-22T17:03:22.000Z","expiryDate":"2022-03-21","odometerValue":"160614","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"All rear position lamps are dim","type":"ADVISORY"},{"dangerous":false,"text":"Brake pad warning lamp is on but pads not worn to the sensors","type":"ADVISORY"},{"dangerous":false,"text":"Power steering system worn but functioning as intended","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"581352947619","completedDate":"2021-03-19T11:16:43.000Z","expiryDate":null,"odometerValue":"160613","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Front Headlamp aim too low (4.1.2 (a))","type":"MAJOR"},{"dangerous":false,"text":"All rear position lamps are dim","type":"ADVISORY"},{"dangerous":false,"text":"Brake pad warning lamp is on but pads not worn to the sensors","type":"ADVISORY"},{"dangerous":false,"text":"Offside Front Exhaust emissions likely to be affected by an induction leak Turbo hose not sealed to turbo, possibly split (8.2.2.1 (b))","type":"MAJOR"},{"dangerous":false,"text":"Power steering system worn but functioning as intended","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"661548477420","completedDate":"2020-03-20T09:31:41.000Z","expiryDate":"2021-03-19","odometerValue":"158896","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Rear Brake hose slightly deteriorated (1.1.12 (b) (ii))","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Brake hose slightly deteriorated (1.1.12 (b) (ii))","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Service brake binding but not excessively (1.2.1 (f))","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"351144956053","completedDate":"2019-03-14T16:43:15.000Z","expiryDate":"2020-03-17","odometerValue":"151026","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"492977327213","completedDate":"2019-03-14T10:17:39.000Z","expiryDate":null,"odometerValue":"151026","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Front Headlamp not working on dipped beam (4.1.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Front Service brake excessively binding (1.2.1 (f))","type":"MAJOR"},{"dangerous":false,"text":"Nearside Rear Tyre obviously under inflated (5.2.3 (h) (i))","type":"MINOR"},{"dangerous":false,"text":"Nearside Rear Nail in tyre","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"539088525351","completedDate":"2018-03-15T17:20:17.000Z","expiryDate":"2019-03-17","odometerValue":"143600","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Rear Child Seat fitted not allowing full inspection of adult belt","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Child Seat fitted not allowing full inspection of adult belt","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"897537506912","completedDate":"2017-03-18T11:07:02.000Z","expiryDate":"2018-03-17","odometerValue":"134701","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"471472446645","completedDate":"2017-03-18T11:07:01.000Z","expiryDate":null,"odometerValue":"134701","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Windscreen washer provides insufficient washer liquid (8.2.3)","type":"PRS"},{"dangerous":false,"text":"Headlamp aim too high both (1.8)","type":"PRS"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"208115526367","completedDate":"2016-05-16T08:24:28.000Z","expiryDate":"2017-05-15","odometerValue":"133339","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"839464935182","completedDate":"2015-05-14T10:24:51.000Z","expiryDate":"2016-05-14","odometerValue":"126848","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"785654835193","completedDate":"2015-05-14T10:24:51.000Z","expiryDate":null,"odometerValue":"126848","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Stop lamp not working (1.2.1b)","type":"PRS"},{"dangerous":false,"text":"Offside Stop lamp not working (1.2.1b)","type":"PRS"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"699305134143","completedDate":"2014-05-15T11:07:24.000Z","expiryDate":"2015-05-14","odometerValue":"121052","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"396644203025","completedDate":"2013-01-04T11:38:30.000Z","expiryDate":"2014-01-03","odometerValue":"113700","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"445873162141","completedDate":"2012-06-11T12:21:54.000Z","expiryDate":"2013-06-10","odometerValue":"111029","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Front Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"},{"dangerous":false,"text":"Offside Front Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"},{"dangerous":false,"text":"Parking brake: parking brake efficiency only just met. It would appear that the braking system requires adjustment or repair. (3.7.B.7)","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"608313451195","completedDate":"2011-06-02T14:32:46.000Z","expiryDate":"2012-06-02","odometerValue":"103870","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside front brake juddering slightly (3.7.A.2b)","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"749263751178","completedDate":"2011-06-02T13:27:02.000Z","expiryDate":null,"odometerValue":"103868","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Front Tyre tread depth below requirements of 1.6mm (4.1.E.1)","type":"FAIL"},{"dangerous":false,"text":"Nearside front brake juddering slightly (3.7.A.2b)","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"531284850105","completedDate":"2010-06-03T12:40:02.000Z","expiryDate":"2011-06-02","odometerValue":"98275","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"924173759146","completedDate":"2009-06-02T12:18:17.000Z","expiryDate":"2010-06-01","odometerValue":"91610","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Rear Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"}]}]}';
        return json_decode($str, true);
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
