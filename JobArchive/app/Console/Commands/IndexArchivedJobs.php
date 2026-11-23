<?php

namespace App\Console\Commands;

use App\elasticsearch\Indexer;
use App\Models\ArchivedJobModel;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class IndexArchivedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-archived-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Indexer $indexer, Client $client)
    {
         $aExistsParams = [
            "index" => $indexer::INDEX_NAME
        ];

        if ($client->indices()->exists($aExistsParams)->asBool()) {

            $progressBar = $this->output->createProgressBar(ArchivedJobModel::count());
            $start = microtime(true);
            $indexer->indexJobs(fn (int $iJobsCount) => $progressBar->advance($iJobsCount));
            $end = microtime(true);
            $progressBar->finish();

            $this->info("\n Jobs in index " . $indexer::INDEX_NAME . " indexed. Time: " . round($end - $start));

            return self::SUCCESS;

        } else {
            $this->warn("Index " . $indexer::INDEX_NAME . " doesn't exist. Use php artisan app:create-archived-job-index to create it.");
            return self::FAILURE;
        }
    }
}
