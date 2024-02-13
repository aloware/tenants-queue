<?php

namespace Aloware\TenantsQueue\Repositories;

trait RedisKeys
{
    /**
     * Get tenant queue redis key name.
     *
     * @return string
     */
    private function queueKey($tenant)
    {
        return sprintf(
            '%s:%s:jobs',
            $this->tenantsQueueKeyPrefix(),
            $tenant
        );
    }

    /**
     * Get tenants list redis key name.
     *
     * @return string
     */
    private function queueTenantsListKeyName()
    {
        return sprintf(
            '%s-tenants-list',
            $this->tenantsQueueKeyPrefix()
        );
    }

    /**
     * Get tenants queue prefix.
     *
     * @return string
     */
    private function tenantsQueueKeyPrefix()
    {
        return config('tenants-queue.key_prefix');
    }
}
