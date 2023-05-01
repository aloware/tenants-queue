<?php

namespace Aloware\TenantsQueue\Http\Controllers;

use Aloware\TenantsQueue\Facades\TenantsQueue;

class MonitoringController extends Controller
{
    /**
     * Get the key performance stats for the dashboard.
     *
     * @return array
     */
    public function index()
    {
        $queues = TenantsQueue::queuesWithPartitions();
        return response()->json($queues);
    }

    /**
     * Get the key performance stats for the dashboard.
     *
     * @return array
     */
    public function failedQueues()
    {
        $queues = TenantsQueue::failedQueuesWithPartitions();
        return response()->json($queues);
    }

    /**
     * Get failed queue partitions
     *
     * @return array
     */
    public function failedQueuePartitions($queue)
    {
        $partitions = TenantsQueue::failedPartitionsWithCount($queue);
        return response()->json($partitions);
    }

}
