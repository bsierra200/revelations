<?php

/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 25/07/18
 * Time: 12:37 PM
 */

namespace BuhoLegalSearch\Service;


use BitAwsS3\Service\S3Uploader;
use GuzzleHttp\Client as GuzzleClient;
use Logger\Service\BlErrorLogger;
use Logger\Service\SimpleFileLogger;

class BuhoLegalClient
{
    /**
     * @var GuzzleClient
     */
    private static $guzzleClient;

    /**
     * @var string
     */
    protected $urlBase = "api4.buholegal.com/search";

    /**
     * @var string
     */
    protected $urlBaseV3 = "https://api.buholegal.com/";

    /**
     * @var string
     */
    protected $endPointToken = "apikey/";

    /**
     * @var string
     */
    protected $endPointSearch = "busqueda/";

    /**
     * @var string
     */
    protected $apiKey = "6a4f31d3e9cc622e6co4f4427d115856522f5758b6212ffa562507b5";

    /**
     * @var string
     */
    protected $apiUser = 'blackinntech';

    /**
     * @var string
     */
    protected $apiPassword = 'abracadabra';

    /**
     * @var string
     */
    protected $token = null;

    /**
     * @var S3Uploader
     */
    protected $s3Uploader;

    /**
     * @var BlErrorLogger
     */
    protected $blErrorLogger;

    /**
     * @var array
     */
    protected $statesBl = [
        'fed' => "FEDERAL",
        'fca' => "FEDERAL",
        'juntafederal' => "FEDERAL",
        'juntalocal' => "CIUDAD DE MEXICO",
        'junlocdf' => "CIUDAD DE MEXICO",
        'ags' => "AGUASCALIENTES",
        'bc' => "BAJA CALIFORNIA",
        'bcs' => "BAJA CALIFORNIA SUR",
        'chi' => "CHIAPAS",
        'chih' => "CHIHUAHUA",
        'coa' => "COAHUILA",
        'col' => "COLIMA",
        'df' => "CIUDAD DE MEXICO",
        'dfloc' => "CIUDAD DE MEXICO",
        'cdmx_admin' => "CIUDAD DE MEXICO",
        'dur' => "DURANGO",
        'edomex' => "ESTADO DE MEXICO",
        'edomexlab' => "ESTADO DE MEXICO",
        'gua' => "GUANAJUATO",
        'guanalaboral' => "GUANAJUATO",
        'gue' => "GUERRERO",
        'hil' => "HIDALGO",
        'jal' => "JALISCO",
        'mic' => "MICHOACAN",
        'mor' => "MORELOS",
        'nay' => "NAYARIT",
        'nleon' => "NUEVO LEON",
        'pue' => "PUEBLA",
        'oax' => "OAXACA",
        'que' => "QUERETARO",
        'qui' => "QUINTANAROO",
        'slp' => "SAN LUIS POTOSI",
        'sin' => "SINALOA",
        'son' => "SONORA",
        'sonlab' => "SONORA",
        'tab' => "TABASCO",
        'tam' => "TAMAULIPAS",
        'ver' => "VERACRUZ",
        'yuc' => "YUCATAN",
        'zac' => "ZACATECAS"
    ];

    private $circuitsBl = [
        0 => NULL,
        1 => "PRIMER CIRCUITO",
        2 => "SEGUNDO CIRCUITO",
        3 => "TERCER CIRCUITO",
        4 => "CUARTO CIRCUITO",
        5 => "QUINTO CIRCUITO",
        6 => "SEXTO CIRCUITO",
        7 => "SEPTIMO CIRCUITO",
        8 => "OCTAVO CIRCUITO",
        9 => "NOVENO CIRCUITO",
        10 => "DECIMO CIRCUITO",
        11 => "DECIMO PRIMER CIRCUITO",
        12 => "DECIMO SEGUNDO CIRCUITO",
        13 => "DECIMO TERCER CIRCUITO",
        14 => "DECIMO CUARTO CIRCUITO",
        15 => "DECIMO QUINTO CIRCUITO",
        16 => "DECIMO SEXTO CIRCUITO",
        17 => "DECIMO SEPTIMO CIRCUITO",
        18 => "DECIMO OCTAVO CIRCUITO",
        19 => "DECIMO NOVENO CIRCUITO",
        20 => "VIGESIMO CIRCUITO",
        21 => "VIGESIMO PRIMER CIRCUITO",
        22 => "VIGESIMO SEGUNDO CIRCUITO",
        23 => "VIGESIMO TERCER CIRCUITO",
        24 => "VIGESIMO CUARTO CIRCUITO",
        25 => "VIGESIMO QUINTO CIRCUITO",
        26 => "VIGESIMO SEXTO CIRCUITO",
        27 => "VIGESIMO SEPTIMO CIRCUITO",
        28 => "VIGESIMO OCTAVO CIRCUITO",
        29 => "VIGESIMO NOVENO CIRCUITO",
        30 => "TRIGESIMO CIRCUITO",
        31 => "TRIGESIMO PRIMERO CIRCUITO",
        32 => "TRIGESIMO SEGUNDO CIRCUITO",
    ];


    /**
     * BuhoLegalClient constructor.
     * @param S3Uploader $s3Uploader
     */
    public function __construct(S3Uploader $s3Uploader, BlErrorLogger $blErrorLogger)
    {
        $this->s3Uploader = $s3Uploader;
        $this->blErrorLogger = $blErrorLogger;
    }

    /**
     * @param $personData
     * @param string $searchMode
     * @return mixed
     */
    public function buhoLegalSearch($personData, $searchMode = "aproximado", $persona = "fisica")
    {
        //new
        $results = $this->searchRequestV3($personData, $persona);
        if (!key_exists('resultados', $results)) {
            $logData = "WITHOUT RESULTS KEY!! Name:" . implode(" ", $personData) . " Results:" . json_encode($results);
            //SimpleFileLogger::log("bl_no_results".strftime("%d%m%Y",time()).".log", $logData);
            $this->blErrorLogger->log("no-result", $logData, implode(" ",$personData));

            $results['resultados'] = [];
        }

        if (count($results['resultados']) == 0) {
            $logData = "NO RESULTS!! Name:" . implode(" ", $personData);
            $this->blErrorLogger->log("no-result", $logData, implode(" ",$personData));
            //SimpleFileLogger::log("bl_no_results".strftime("%d%m%Y",time()).".log", $logData);
        }

        $normalizedResults = $this->normalizeResultsV3($results['resultados']);

        //TODO: To later reports errors and do retries
        /*if (isset($results['error']) && $results['error']===true) {
            $normalizedResults['error'] = true;
        }*/

        //old
        // $results = $this->searchRequest($personData, $searchMode);
        // $normalizedResults = $this->normalizeResults($results["results"]);

        return $normalizedResults;
    }

    /**
     * Lazy  Loads the guzzle client
     * @static
     * @return Client
     */
    private static function getGuzzleClient()
    {
        if (static::$guzzleClient === NULL) {
            static::$guzzleClient = new GuzzleClient(
                [
                    'http_errors' => false,
                    'curl' => [
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_TIMEOUT_MS => 0,
                        CURLOPT_CONNECTTIMEOUT => 0,
                    ]
                ]
            );
        }
        return static::$guzzleClient;
    }

    /**
     * Actually Do the Request
     *
     * @param array $infoCandidate
     * @return string
     */
    private function searchRequest(array $personData, $searchMode = "aproximado")
    {
        $name = $personData['name'] . " " . $personData['lastName'] . " " . $personData['secondLastName'];
        $url = $this->urlBase . "?" . "apikey={$this->apiKey}&parte=ambos&criterio={$searchMode}&nombre={$name}&entidad=todas";

        $response = static::getGuzzleClient()->get($url, [
            'User-Agent' => 'BT',
            'verify' => false
        ]);

        if ($response->getStatusCode() !== 200) {
            return ['results' => [], 'statusCode' => $response->getStatusCode(), 'bodyString' => (string)$response->getBody()];
        } else {
            $jsonResults = (string)$response->getBody();
            $arrayResults = json_decode($jsonResults, true);

            $bucketName = "backupdb-revelations";
            $fileData = $jsonResults;
            $this->s3Uploader->uploadFile($processName, $fileData, $bucketName);
            $processName = str_replace(" ", "_", $name);

            if (count($arrayResults['results']) > 0)


                return $arrayResults;
        }
    }

    /**
     * @param array $searchResults
     * @return array
     */
    private function normalizeResults(array $searchResults)
    {
        $normalizedResults = [];
        foreach ($searchResults as $stateResult => $results) {
            foreach ($results as $result) {
                $altResult = [];
                $altResult['state'] = $this->statesBl[$stateResult];
                $altResult['case_file'] = $result['expediente'] ?? NULL;
                $altResult['circuit'] = (isset($result['circuito_id'])) ? $this->circuitsBl[$result['circuito_id']] : "";
                $altResult['applicant'] = $result['actor'] ?? NULL;
                $altResult['defendant'] = $result['demandado'] ?? NULL;
                $altResult['judgement_type'] = $result['tipo'] ?? NULL;
                $altResult['extra_fields']['organ'] = $result['juzgado'] ?? NULL;
                $altResult['extra_fields']['agreement_date'] = $result['fecha'] ?? NULL;
                $altResult['extra_fields']['caption'] = $result['tipo'] ?? NULL;
                $altResult['extra_fields']['circuit'] = (isset($result['circuito_id'])) ? $this->circuitsBl[$result['circuito_id']] : "";
                $altResult['extra_fields']['applicant'] = $result['actor'] ?? NULL;
                $altResult['extra_fields']['defendant'] = $result['demandado'] ?? NULL;
                $normalizedResults[] = $altResult;
                unset($altResult);
            }
        }

        return $normalizedResults;
    }

    /**
     * @param array $searchResults
     * @return array
     */
    private function normalizeResultsV3(array $searchResults)
    {

        $normalizedResults = [];
        foreach ($searchResults as $stateResult => $results) {
            $altResult = [];
            $stateRecord = $results['entidad'] ?? NULL;

            foreach ($results['expedientes'] as $result) {
                $altResult['case_file'] = $result['expediente'] ?? NULL;
                $altResult['circuit'] = (isset($result['circuito_id'])) ? $this->circuitsBl[$result['circuito_id']] : "";
                $altResult['applicant'] = $result['actor'] ?? NULL;
                $altResult['defendant'] = $result['demandado'] ?? NULL;
                $altResult['judgement_type'] = $result['tipo'] ?? NULL;
                $altResult['type'] = $result['fuero'] ?? NULL;
                $altResult['state'] = $stateRecord ?? NULL;
                $altResult['extra_fields']['organ'] = $result['juzgado'] ?? NULL;
                $altResult['extra_fields']['agreement_date'] = $result['fecha'] ?? NULL;
                $altResult['extra_fields']['caption'] = $result['tipo'] ?? NULL;
                $altResult['extra_fields']['circuit'] = (isset($result['circuito_id'])) ? $this->circuitsBl[$result['circuito_id']] : "";
                $altResult['extra_fields']['applicant'] = $result['actor'] ?? NULL;
                $altResult['extra_fields']['defendant'] = $result['demandado'] ?? NULL;
                $normalizedResults[] = $altResult;
                unset($altResult);
            }
        }

        return $normalizedResults;
    }

    /**
     * Actually Do the Request
     *
     * @param array $infoCandidate
     * @return string|array
     */
    private function searchRequestV3(array $personData, $persona = "fisica")
    {
        $this->getToken();

        $name = $personData['name'];
        $paterno = $personData['lastName'];
        $materno = $personData['secondLastName'];
        $url = $this->urlBaseV3 . $this->endPointSearch . "?" . "persona={$persona}&nombre={$name}&paterno={$paterno}&materno={$materno}";

        $response = static::getGuzzleClient()->get($url, [
            'headers' => [
                'Authorization' => "Token {$this->token}",
            ],
            'verify' => false,
        ]);

        if ($response->getStatusCode() !== 200) {
            $logData = "HTTP Response Status Code: {$response->getStatusCode()} -  Response Body:" . (string)$response->getBody();
            $this->blErrorLogger->log("error", $logData,implode(" ",$personData));
            //SimpleFileLogger::log("bl_errors".strftime("%d%m%Y",time()).".log", $logData);
            return [
                'results' => [],
                'statusCode' => $response->getStatusCode(),
                'bodyString' => (string)$response->getBody(),
                'error' => true
            ];
        } else {
            $jsonResults = (string)$response->getBody();
            $arrayResults = json_decode($jsonResults, true);

            $bucketName = "backupdb-revelations";
            $fileData = $jsonResults;
            $processName = str_replace(" ", "_", $name);

            if (count($arrayResults) > 0) {
               // $this->s3Uploader->uploadFile($processName, $fileData, $bucketName);
            }

            return $arrayResults;
        }
    }

    /**
     * Actually Do the Request
     *
     * @return array|string
     */
    private function getToken()
    {
        $tokenFilename = __DIR__ . "/../../../../data/blToken";
        if (file_exists($tokenFilename)) {
            //SimpleFileLogger::log("bl_errors.log", "Se reutilizó el token de BL");
            $modTime = filemtime($tokenFilename);
            if ((time() - $modTime) < (24 * 3600)) {
                $tokenData = file_get_contents($tokenFilename);
                return $this->token = $tokenData;
            }
        }

        $url = $this->urlBaseV3 . $this->endPointToken;
        $args = [
            'body' => json_encode([
                'username' => $this->apiUser,
                'password' => $this->apiPassword
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        $response = static::getGuzzleClient()->post($url, $args);
        if ($response->getStatusCode() !== 200) {
            $logData = "TOKEN REQUEST - HTTP Response Status Code: {$response->getStatusCode()} -  Response Body:" . (string)$response->getBody();
            $this->blErrorLogger->log("error", $logData);
            //SimpleFileLogger::log("bl_errors.log", $logData);
            return [
                'results' => [],
                'statusCode' => $response->getStatusCode(),
                'bodyString' => (string)$response->getBody(),
                'error' => true
            ];
        } else {
            $jsonResults = (string)$response->getBody();
            $arrayResults = json_decode($jsonResults, true);
            $token = $arrayResults['token'];

            file_put_contents($tokenFilename, $token);
            $this->token = $token;
        }

    }
}
