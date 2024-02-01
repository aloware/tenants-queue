<?php

namespace Aloware\TenantsQueue;

use Aloware\TenantsQueue\Exceptions\EmptyPartitionNameException;
use Illuminate\Contracts\Bus\Dispatcher;

class PendingDispatch extends \Illuminate\Foundation\Bus\PendingDispatch
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
        $this->job->onQueue(config('tenants-queue.default_queue_name'));
    }

    /**
     * @throws EmptyPartitionNameException
     */
    public function tenant($partition)
    {
        if (empty($partition)) {
            throw new EmptyPartitionNameException();
        }

        $this->job->partition = $partition;

        return $this;
    }

    public function tries($number = 1)
    {
        $this->job->originalJob->maxTries = max((int) $number, 1);

        return $this;
    }

    public function __destruct()
    {
        if (!config('tenants-queue.enabled')) {
            app(Dispatcher::class)->dispatch($this->job->originalJob);
            return ;
        }

        $this->job->addToPartition();

        app(Dispatcher::class)->dispatch($this->job);
    }
}
