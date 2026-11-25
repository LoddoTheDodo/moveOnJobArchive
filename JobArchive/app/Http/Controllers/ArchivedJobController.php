<?php

namespace App\Http\Controllers;

use App\elasticsearch\Indexer;
use App\Models\ArchivedJobModel;
use Elastic\Elasticsearch\Client;
use Illuminate\Http\Request;

class ArchivedJobController extends Controller
{
    public function show(Request $request, ArchivedJobModel $job, Client $client)
    {
        $response = $client->search([
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
                                            '_id' => (string) $job->getKey(),
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
                                    'values' => [(string) $job->getKey()],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $hits = $response->asArray()['hits']['hits'] ?? [];
        $ids = array_column(array_map(
            fn (array $hit) => ['id' => (string) $hit['_id']],
            $hits
        ), 'id');

        $recommended = collect();
        if ($ids !== []) {
            $jobsById = ArchivedJobModel::with(['company', 'category'])
                ->whereIn('id', $ids)
                ->get()
                ->keyBy(fn (ArchivedJobModel $item) => (string) $item->getKey());

            $recommended = collect($ids)
                ->map(fn (string $id) => $jobsById->get($id))
                ->filter();
        }

        return view('jobs.show', [
            'job' => $job->load(['company', 'category']),
            'recommended' => $recommended,
        ]);
    }
}
