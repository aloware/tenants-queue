<?php

namespace Aloware\TenantsQueue\Repositories;

trait RedisKeys
{
    private function partitionKey($queue, $partition, $prefix = '')
    {
        return sprintf(
            '%s%s:%s:%s',
            $this->fairQueueKeyPrefix(),
            $prefix,
            $queue,
            $partition
        );
    }

    private function queueKey($queue, $prefix = '')
    {
        return sprintf(
            '%s%s:%s:*',
            $this->fairQueueKeyPrefix(),
            $prefix,
            $queue
        );
    }

    private function queueTenantsListKeyName()
    {
        return sprintf(
            '%s-tenants-list',
            $this->tenantsQueueKeyPrefix()
        );
    }

    private function tenantsQueueKeyPrefix()
    {
        return config('tenants-queue.key_prefix');
    }
}
