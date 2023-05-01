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
            info('HAMED_LOG_TEST', [
                'queue' => $queue,
            ]);
            return $connection->pop($queue);
        };

        try {
            if (isset(static::$popCallbacks[$this->name])) {
                return (static::$popCallbacks[$this->name])($popJobCallback, $queue);
            }

            foreach (explode(',', $queue) as $queue) {
                if (! is_null($job = $popJobCallback($queue))) {
                    info('HAMED_LOG_NEXT_JOB', [
                        'job' => $job
                    ]);
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
            info('HAMED_LOG_1', [
                'tenantQueue' => $tenantQueue,
                'test' => $connection->getConnectionName()
            ]);
            $job = $connection->pop($tenantQueue);
            info('HAMED_LOG_GET_NEXT_JOB_2', [
                'job' => $job
            ]);
            if ($job) {
                $this->removeTenantFromQueueIndexIfLastJob($queue, $tenantQueue);
                info('HAMED_LOG_GET_NEXT_JOB_3', [
                    'job' => $job
                ]);
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
