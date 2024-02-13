<?php

namespace Aloware\TenantsQueue\Commands;

use Aloware\TenantsQueue\Facades\TenantsQueue;
use Aloware\TenantsQueue\Interfaces\RepositoryInterface;
use Aloware\TenantsQueue\Repositories\RedisKeys;
use Illuminate\Console\Command;

class ClearTenantsNamesList extends Command
{
    use RedisKeys;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants-queue:clear-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Tenants Names List';

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(RepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        TenantsQueue::removeTenantsNamesList();

        $this->info('Tenants Names List Cleared Successfully');
    }

}
