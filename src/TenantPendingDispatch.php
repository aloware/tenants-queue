<?php

namespace Aloware\TenantsQueue;

use Aloware\TenantsQueue\Facades\TenantsQueue;

class TenantPendingDispatch extends \Illuminate\Foundation\Bus\PendingDispatch
{
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

        return $this;
    }
}