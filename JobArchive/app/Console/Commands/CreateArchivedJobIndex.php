<?php

namespace App\Console\Commands;

use App\elasticsearch\Indexer;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class CreateArchivedJobIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-archived-job-index {--force : Drop the index first if it exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ElasticSearch index for archived jobs';

    /**
     * Execute the console command.
     */
    public function handle(Client $client)
    {

        $aExistsParams = [
            "index" => Indexer::INDEX_NAME
        ];

        $aCreateParams = Indexer::mappings();

        if ($client->indices()->exists($aExistsParams)->asBool()) {
            if (! $this->option('force')) {
                $this->warn("Index " . Indexer::INDEX_NAME . " already exists. Use --force to recreate it.");
                return self::FAILURE;
            }

            $client->indices()->delete(['index' => Indexer::INDEX_NAME]);
            $this->info("Deleted existing index " . Indexer::INDEX_NAME);
        } else {
            $client->indices()->create($aCreateParams);
            $this->info("Index " . Indexer::INDEX_NAME . " created.");
            return self::SUCCESS;
        }
    }
}
