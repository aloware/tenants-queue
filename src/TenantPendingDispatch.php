<?php

namespace Aloware\TenantsQueue;

use Aloware\TenantsQueue\Facades\TenantsQueue;
use Aloware\TenantsQueue\Repositories\RedisKeys;

class TenantPendingDispatch extends \Illuminate\Foundation\Bus\PendingDispatch
{
    use RedisKeys;
    /**
     * Create a new pending job dispatch.
     *
     * @param  mixed $job
     * @return void
     */
    public function __construct($job)
    {
        $this->job = $job;
    }

    /**
     * Assign a tenant number to the job.
     *
     * @return TenantPendingDispatch
     */
    public function tenant($tenant): TenantPendingDispatch
    {
        TenantsQueue::addTenantNameToTheList($tenant);

        $this->job->tenant = $tenant;

        // Set the job to be dispatched to the proper tenant queue
        $tenantQueueName = $this->queueKey($tenant);
        $this->onQueue($tenantQueueName);

        return $this;
    }
}
