<?php

namespace JobArchive\App\elasticsearch;

use Elastic\Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class Search {
    public function __construct(private Client $client) {}

    public function getArchivedJobs(int $iPage = 1, int $iPerPage = 10 ) {
        $aSearchQuery = [
            "index" => "archived_jobs",
            "body" => [
                "from" => ($iPage - 1) * $iPerPage,
                "size" => $iPerPage,
                "query" => ['match_all' => new \stdClass()],
                "aggregations" => [
                    "company_agg" => [
                        "terms" => [
                            "field" => "companyName.raw"
                        ],
                        "aggregations" => [
                            "company_info" => [
                                "top_hits" => [
                                    "size" => 1,
                                    "_source" => ["companyName", "companyPrimaryAddress", "companyPrimaryAddressZip", "companyPrimaryAddressCity", "companyUrl", "companyUnsolicitedApplications"]
                                ]
                            ],
                            "jobs_dates" => [
                                "date_histogram" => [
                                    "field" => "found",
                                    'calendar_interval' => 'day',
                                    'format' => 'yyyy-MM-dd',
                                    'min_doc_count' => 1,
                                ]
                            ]
                        ],
                    ],
                    "bucket_count" => [
                        'cardinality' => ['field' => 'companyName.raw'],
                    ]
                ]
            ]
        ];

        $aResponse = $this->client->search($aSearchQuery);

        $buckets = collect($aResponse["aggregations"]["company_agg"]["buckets"]);

        return new LengthAwarePaginator($buckets, $aResponse['aggregations']['bucket_count']['value'], $iPerPage, $iPage);
    }

}
