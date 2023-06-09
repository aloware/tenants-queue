<?php

namespace Aloware\TenantsQueue\Commands;

use Aloware\TenantsQueue\Extensions\TenantsWorker;
use Laravel\Horizon\Console\WorkCommand;
use Illuminate\Contracts\Cache\Repository as Cache;

class TenantsQueueWorker extends WorkCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'tenants-queue:work
                            {connection? : The name of the queue connection to work}
                            {--name=default : The name of the worker}
                            {--delay=0 : The number of seconds to delay failed jobs (Deprecated)}
                            {--backoff=0 : The number of seconds to wait before retrying a job that encountered an uncaught exception}
                            {--max-jobs=0 : The number of jobs to process before stopping}
                            {--max-time=0 : The maximum number of seconds the worker should run}
                            {--daemon : Run the worker in daemon mode (Deprecated)}
                            {--force : Force the worker to run even in maintenance mode}
                            {--memory=128 : The memory limit in megabytes}
                            {--once : Only process the next job on the queue}
                            {--stop-when-empty : Stop when the queue is empty}
                            {--queue= : The names of the queues to work}
                            {--sleep=3 : Number of seconds to sleep when no job is available}
                            {--rest=0 : Number of seconds to rest between jobs}
                            {--supervisor= : The name of the supervisor the worker belongs to}
                            {--timeout=60 : The number of seconds a child process can run}
                            {--tries=0 : Number of times to attempt a job before logging it failed}';

    protected $description = 'Start processing jobs on the queue as a custom worker';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    public function __construct(TenantsWorker $worker, Cache $cache)
    {
        parent::__construct($worker, $cache);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();
    }
}