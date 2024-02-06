<?php

namespace Aloware\TenantsQueue;

use Aloware\TenantsQueue\Repositories\RedisRepository;

class TenantPendingDispatch extends \Illuminate\Foundation\Bus\PendingDispatch
{
    use RedisRepository;
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
        $this->addTenantNameToTheList($tenant);

        $this->job->tenant = $tenant;

        return $this;
    }
}