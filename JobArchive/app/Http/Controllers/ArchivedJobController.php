<?php

namespace App\Http\Controllers;

use App\elasticsearch\Indexer;
use Elastic\Elasticsearch\Client;

class ArchivedJobController extends Controller
{
    public function show(string $job, Client $client)
    {
        $jobResponse = $client->search([
            'index' => Indexer::INDEX_NAME,
            'body' => [
                'size' => 1,
                'query' => [
                    'ids' => [
                        'values' => [$job],
                    ],
                ],
            ],
        ])->asArray();

        $jobHit = $jobResponse['hits']['hits'][0] ?? null;
        abort_if($jobHit === null, 404);

        $job = $jobHit['_source'];
        $job['id'] = (string) $jobHit['_id'];

        $recommendationResponse = $client->search([
            'index' => Indexer::INDEX_NAME,
            'body' => [
                'size' => 5,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'more_like_this' => [
                                    'fields' => ['title', 'extractedContent'],
                                    'like' => [
                                        [
                                            '_index' => Indexer::INDEX_NAME,
                                            '_id' => $job['id'],
                                        ],
                                    ],
                                    'min_term_freq' => 1,
                                    'min_doc_freq' => 2,
                                    'max_query_terms' => 25,
                                ],
                            ],
                        ],
                        'must_not' => [
                            [
                                'ids' => [
                                    'values' => [$job['id']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ])->asArray();

        $recommended = collect($recommendationResponse['hits']['hits'] ?? [])
            ->map(function (array $hit) {
                $job = $hit['_source'];
                $job['id'] = (string) $hit['_id'];

                return $job;
            });

        return view('jobs.show', compact('job', 'recommended'));
    }
}
