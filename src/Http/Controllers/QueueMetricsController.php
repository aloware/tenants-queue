<?php

namespace Aloware\TenantsQueue\Http\Controllers;

use Aloware\TenantsQueue\Facades\TenantsQueue;
use Aloware\TenantsQueue\Http\Requests\PartitionJobsRequest;

class QueueMetricsController extends Controller
{
    /**
     * Get the paritions of a queue.
     *
     * @return array
     */

    public function queuePartitions($queue)
    {
        $partitions = TenantsQueue::partitionsWithCount($queue);
        return response()->json($partitions);
    }

    /**
     * Get the paritions of a queue.
     *
     * @return array
     */

    public function partitionJobs(PartitionJobsRequest $request, $queue, $partition)
    {
        $jobs = TenantsQueue::jobs($queue, $partition);
        return response()->json($jobs);
    }

    /**
     * Get the failed jobs of a partition.
     *
     * @return array
     */

    public function failedPartitionJobs(PartitionJobsRequest $request, $queue, $partition)
    {
        $jobs = TenantsQueue::failedJobs($queue, $partition);
        return response()->json($jobs);
    }

    /**
     * Get job payload.
     *
     * @return object
     */

    public function jobPreview($queue, $partition, $index)
    {
        $job = TenantsQueue::job($queue, $partition, $index);
        return response()->json($job);
    }

    /**
     * Get failed-job payload.
     *
     * @return object
     */

    public function failedJobPreview($queue, $partition, $index)
    {
        $job = TenantsQueue::failedJob($queue, $partition, $index);
        return response()->json($job);
    }

}
