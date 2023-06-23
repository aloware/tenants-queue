<?php

namespace Aloware\TenantsQueue\Extensions;

use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Redis;
use Throwable;

class TenantsWorker extends Worker
{
    /**
     * Get the next job from the queue connection.
     *
     * @param  \Illuminate\Contracts\Queue\Queue  $connection
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    protected function getNextJob($connection, $queue)
    {
        $popJobCallback = function ($queue) use ($connection) {
            return $connection->pop($queue);
        };

        try {
            if (isset(static::$popCallbacks[$this->name])) {
                return (static::$popCallbacks[$this->name])($popJobCallback, $queue);
            }

            foreach (explode(',', $queue) as $queue) {
                if (! is_null($job = $popJobCallback($queue))) {
                    return $job;
                }
            }
        } catch (Throwable $e) {
            $this->exceptions->report($e);

            $this->stopWorkerIfLostConnection($e);

            $this->sleep(1);
        }
    }

    protected function getNextJob2($connection, $queue)
    {
        $tenantQueues = $this->getTenantQueues($queue);

        if (empty($tenantQueues)) {
            return null;
        }

        shuffle($tenantQueues);

        foreach ($tenantQueues as $tenantQueue) {
            $job = $connection->pop($tenantQueue);
            if ($job) {
                $this->removeTenantFromQueueIndexIfLastJob($queue, $tenantQueue);
                return $job;
            }
        }

        return null;
    }

    protected function getTenantQueues($queue)
    {
        $redis = Redis::connection();
        // $tenantIds = $this->manager->connection('custom_redis')->getRedis()->zrange("fq:{$queue}:index", 0, -1);
        $tenantIds = $redis->smembers('tenants');
        return array_map(fn($tenantId) => "fq:{$queue}:{$tenantId}", $tenantIds);
    }

    protected function removeTenantFromQueueIndexIfLastJob($queue, $tenantQueue)
    {
        $redis = $this->manager->connection('custom_redis')->getRedis();

        if ($redis->llen($tenantQueue) === 0) {
            $tenantId = explode(':', $tenantQueue)[2];
            $redis->zrem("fq:{$queue}:index", $tenantId);
        }
    }
}
