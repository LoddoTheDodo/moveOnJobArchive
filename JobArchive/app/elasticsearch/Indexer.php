<?php

namespace App\elasticsearch;

use App\Models\ArchivedJobModel;
use Elastic\Elasticsearch\Client;
use Exception;

class Indexer {

    public function __construct(
        private JobDocument $jobDocument,
        private Client $client
    ) {}

    public const INDEX_NAME = "archived_jobs";

    public static function mappings() {

        return [
            "index" => self::INDEX_NAME,
            "body" => [
                "settings" => [
                    "max_result_window" => 100000
                ],
                "mappings" => [
                    "properties" => [
                        "title" => [
                            "type" => "text",
                            "fields" => [
                                "raw" => ["type" => "keyword"]
                            ]
                        ],
                        "extractedContent" => ["type" => "text"],
                        "found" => [
                            "type" => "date",
                            "format" => "year_month_day"
                        ],
                        "lastExamined" => [
                            "type" => "date",
                            "format" => "year_month_day"
                        ],
                        "statusSince" => [
                            "type" => "date",
                            "format" => "year_month_day"
                        ],
                        "companyId" => [
                            "type" => "integer"
                        ],
                        "companyRobotId" => [
                            "type" => "integer"
                        ],
                        "companyName" => [
                            "type" => "text",
                            "fields" => [
                                "raw" => ["type" => "keyword"]
                            ]
                        ],
                        "companyPrimaryAddress" => [
                            "type" => "text"
                        ],
                        "companyPrimaryAddressZip" => [
                            "type" => "keyword"
                        ],
                        "companyPrimaryAddressCity" => [
                            "type" => "text",
                            "fields" => [
                                "raw" => ["type" => "keyword"]
                            ]
                        ],
                        "companyUrl" => [
                            "type" => "text"
                        ],
                        "companyBaseDescription" => [
                            "type" => "text"
                        ],
                        "companyUnsolicitedApplications" => [
                            "type" => "integer"
                        ],
                        "companyLon" => [
                            "type" => "double"
                        ],
                        "companyLat" => [
                            "type" => "double"
                        ],
                        "jobData" => [
                            "properties" => [
                                "id" => ["type" => "long"],
                                "companyId" => ["type" => "keyword"],
                                "isJob" => ["type" => "keyword"],
                                "title" => ["type" => "text"],
                                "resume" => ["type" => "text"],
                                "extractedContent" => ["type" => "text"],
                                "lastExamined" => ["type" => "date"],
                                "found" => ["type" => "date"],
                                "statusSince" => ["type" => "date"]
                            ]
                        ],
                        "companyData" => [
                            "properties" => [
                                "id" => ["type" => "long"],
                                "robotId" => ["type" => "keyword"],
                                "companyName" => ["type" => "text"],
                                "primaryAddress" => ["type" => "text"],
                                "primaryAddressZip" => ["type" => "keyword"],
                                "primaryAddressCity" => ["type" => "text"],
                                "url" => ["type" => "text"],
                                "baseDescription" => ["type" => "text"],
                                "unsolicitedApplications" => ["type" => "integer"],
                                "lon" => ["type" => "double"],
                                "lat" => ["type" => "double"]
                            ]
                        ],
                        "categoryData" => [
                            "type" => "nested",
                            "properties" => [
                                "id" => ["type" => "long"],
                                "parentId" => ["type" => "long"],
                                "title" => ["type" => "text"],
                                "sequence" => ["type" => "long"]
                            ]
                        ],
                        "categories" => [
                            "type" => "nested",
                            "properties" => [
                                "id" => ["type"=> "integer"],
                                "title" => ["type" => "keyword"],
                                "sequence" => ["type" => "integer"]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function indexJobs(?callable $onChunk = null) {

        ArchivedJobModel::with(['company', 'category'])->chunkById(500, function ($cJobs) use ($onChunk) {

            $aBulkBody = [];

            foreach ($cJobs as $job) {
                $aIndexInfo = [
                    "_index" => self::INDEX_NAME,
                    "_id" => $job->id
                ];

                $aBulkBody[] = ["index" => $aIndexInfo];
                $aBulkBody[] = $this->jobDocument->createJobDocument($job);
            }

            try {
                $response = $this->client->bulk([
                    'body' => $aBulkBody
                ]);

                if (isset($response['errors']) && $response['errors'] === true) {
                    $aErrors = [];
                    foreach($response["items"] as $item) {
                        if (isset($item["index"]["error"])) {
                            $sError = json_encode($item["index"]["error"], JSON_PRETTY_PRINT);
                            $aErrors[] = $sError;
                        }
                    }

                    $sReadableErrors = json_encode($aErrors, JSON_PRETTY_PRINT);
                    throw new Exception("Error message on index operation: \n {$sReadableErrors}");

                } else {
                    $onChunk($cJobs->count());
                    $aBulkBody = [];
                }
            } catch (Exception $e) {
                throw new Exception("An error occured", 0, $e);
            }

        });

    }
}
