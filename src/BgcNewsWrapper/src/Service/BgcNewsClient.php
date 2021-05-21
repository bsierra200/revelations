<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 26/07/18
 * Time: 09:21 AM
 */

namespace BgcNewsWrapper\Service;


use GuzzleHttp\Client;

class BgcNewsClient
{
    /**
     * @var Client
     */
    private static $guzzleClient;

    /**
     * @var string
     */
    protected $urlBase = "apibgcnews.blacktrust.net";

    public function bgcNewsSearch($personData)
    {
        $name = $personData['name'] . " " . $personData['lastName'] . " " . $personData['secondLastName'];
        $results = $this->searchRequest($name);

        return $results;
    }

    /**
     * @param $candidate
     * @return array|mixed
     */
    private function searchRequest($candidate)
    {
        $path = '/news?name=';
        $url = $this->urlBase . $path . $candidate;

        $response = static::getGuzzleClient()->get($url, [
            'User-Agent' => 'visionUber',
            'verify' => false
        ]);

        if ($response->getStatusCode() !== 200) {
            $results = [];
        } else {
            $jsonResults = (string)$response->getBody();
            $results = json_decode($jsonResults, true);
        }

        return $results;
    }


    /**
     * Lazy  Loads the guzzle client
     * @static
     * @return Client
     */
    private static function getGuzzleClient()
    {
        if (static::$guzzleClient === NULL) {
            static::$guzzleClient = new Client(
                [
                    'http_errors' => false,
                    'curl' => [
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_TIMEOUT_MS => 0,
                        CURLOPT_CONNECTTIMEOUT => 0,
                    ]
                ]);
        }
        return static::$guzzleClient;
    }

}