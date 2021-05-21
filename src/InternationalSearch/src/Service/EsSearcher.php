<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/11/18
 * Time: 11:42 AM
 */

namespace InternationalSearch\Service;


use Elasticsearch\Client;

class EsSearcher
{
    /**
     * @var Client
     */
    private $elasticSearchClient;

    /**
     * @var string
     */
    private $indexName = 'cjf';

    /**
     * @var string
     */
    private $docType = 'results';

    /**
     * Performs a partial fulltext search on the revelations index, it does not use the exact term,
     *
     * @param $name
     * @param $lastName
     * @param $secondLastName
     * @return array sorted by an score
     */
    public function partialFulltextSearch($name, $lastName, $secondLastName): array
    {
        $fullName = strtoupper($name . " " . $lastName . " " . $secondLastName);

        $params = [
            'index' => $this->indexName,
            'type' => $this->docType,
            'body' => [
                "query" => [
                    "multi_match" => [
                        "query" => $fullName,
                        "operator" => "AND",
                        "fields" => ["defendant", "applicant"]
                    ]
                ]
            ]
        ];

        $results = $this->elasticSearchClient->search($params);
        return $this->processResults($results);
    }

    /**
     * Performs a exact text search on the revelations index
     *
     * @param $name
     * @return array
     */
    public function exactTextSearch($name, $lastName, $secondLastName, $backwards = false): array
    {
        //prepare the values to do the search
        $fullName = strtoupper($name . " " . $lastName . " " . $secondLastName);
        if ($backwards === true) {
            $backwardsName = strtoupper( $lastName . " " . $secondLastName." ".$name);

            $withDot=$fullName.".";
            $withSpaceDot=$fullName." .";
            $backwardsWithDot=$backwardsName.".";
            $backwardsWithSpaceDot=$backwardsName." .";

            $toSearch = [$fullName,$backwardsName,$withDot,$withSpaceDot,$backwardsWithDot,$backwardsWithSpaceDot];
            $filter = "terms";
        } else {
            $toSearch = $fullName;
            $filter = "term";
        }

        $params = [
            'index' => $this->indexName,
            'type' => $this->docType,
            'body' => [
                'query' => [
                    'constant_score' => [
                        'filter' => [
                            $filter => [
                                'applicant.keyword' => $toSearch
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $applicantResults = $this->processResults($this->elasticSearchClient->search($params));

        $params = [
            'index' => $this->indexName,
            'type' => $this->docType,
            'body' => [
                'query' => [
                    'constant_score' => [
                        'filter' => [
                            $filter => [
                                'defendant.keyword' => $toSearch
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $defendantResults = $this->processResults($this->elasticSearchClient->search($params));

        $results['results']=array_merge($applicantResults['results'],$defendantResults['results']);
        return $results;
    }


    /**
     * @param array $results
     * @return array
     */
    private function processResults(array $results): array
    {
        //If there is no hits returns an empty array
        if (count($results['hits']['hits']) == 0) {
            return ['results' => []];
        } else {
            $resultsProcessed['results'] = [];
            $resultCounter = 0;
            foreach ($results['hits']['hits'] as $processedResult) {
                $processedResult['_source']['state']=(is_array($processedResult['_source']['state']))?$processedResult['_source']['state'][0]:$processedResult['_source']['state'];
                $resultsProcessed['results'][$resultCounter] = $processedResult['_source'];
                $resultsProcessed['results'][$resultCounter]["id"] = $processedResult['_id'];
                $resultsProcessed['results'][$resultCounter]["score"] = $processedResult['_score'];
                $resultCounter++;
            }
        }

        return $resultsProcessed;
    }

    /**
     * @return Client
     */
    public function getElasticSearchClient(): Client
    {
        return $this->elasticSearchClient;
    }

    /**
     * @param Client $elasticSearchClient
     */
    public function setElasticSearchClient(Client $elasticSearchClient)
    {
        $this->elasticSearchClient = $elasticSearchClient;
    }

    /**
     * @return string
     */
    public function getIndexName(): string
    {
        return $this->indexName;
    }

    /**
     * @param string $indexName
     */
    public function setIndexName(string $indexName)
    {
        $this->indexName = $indexName;
    }

    /**
     * @return string
     */
    public function getDocType(): string
    {
        return $this->docType;
    }

    /**
     * @param string $docType
     */
    public function setDocType(string $docType)
    {
        $this->docType = $docType;
    }
}