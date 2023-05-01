<?php

namespace Aloware\TenantsQueue\Extensions;

use Illuminate\Queue\RedisQueue;

class TenantsRedisQueue extends RedisQueue
{
    protected function createPayloadArray($job, $queue, $data = '')
    {
        $payload = parent::createPayloadArray($job, $queue, $data);

        if (isset($payload['tenantId'])) {
            $this->addTenantToQueueIndex($queue, $payload['tenantId']);
        }

        return $payload;
    }

    protected function addTenantToQueueIndex($queue, $tenantId)
    {
        $this->redis->connection()->zadd("fq:{$queue}:index", [$tenantId => now()]);
    }
}
