<?php

namespace Aloware\TenantsQueue\Http\Controllers;

use Aloware\TenantsQueue\Facades\TenantsQueue;
use Aloware\TenantsQueue\Http\Requests\FakeSignalRequest;
use Aloware\TenantsQueue\Http\Requests\RecoverLostJobsRequest;

class QueueController extends Controller
{
    /**
     * Get the key performance stats for the dashboard.
     *
     * @return array
     */
    public function generateFakeSignal(FakeSignalRequest $request, $queue)
    {
        TenantsQueue::generateFakeSignals($queue, $request->amount);
    }

    /**
     * Get the key performance stats for the dashboard.
     *
     * @return array
     */
    public function recoverLostJobs(RecoverLostJobsRequest $request)
    {
        $recovered_count = TenantsQueue::recoverLost($request->amount);

        return response()->json([
            'recovered' => $recovered_count
        ]);
    }

    /**
     * Retry failed jobs
     *
     * @param string|null $queue
     * @param string|null $partition
     *
     * @return array
     */
    public function retryFailedJobs($queue = null, $partition = null)
    {
        $count = TenantsQueue::retryFailedJobs((array) $queue, (array) $partition);
        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Recover Lost Jobs of a Specific Partition
     *
     * @return array
     */
    public function recoverPartitionLostJobs($queue, $partition)
    {
        $recovered_count = TenantsQueue::recoverPartitionLost($queue, $partition, request()->amount);

        return response()->json([
            'recovered' => $recovered_count
        ]);
    }

    public function purgeFailedJobs($queue = null, $partition = null)
    {
        TenantsQueue::purgeFailedJobs((array) $queue, (array) $partition);
    }

}
