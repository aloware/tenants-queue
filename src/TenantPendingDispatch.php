<?php

namespace Aloware\TenantsQueue;

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
    public function tenant()
    {
        $tenants_size = config('tenants-queue.tenants_size');
        $tenant = rand(1, $tenants_size);

        $this->job->tenant = $tenant;

        return $this;
    }
}