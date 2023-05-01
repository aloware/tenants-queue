<?php

namespace Aloware\TenantsQueue\Http\Controllers;

use Aloware\TenantsQueue\Facades\TenantsQueue;

class DashboardStatsController extends Controller
{
    /**
     * Get the key performance stats for the dashboard.
     *
     * @return array
     */
    public function index()
    {
        $queues = TenantsQueue::queues();
        $totalJobs = TenantsQueue::totalJobsCount($queues);
        $failedQueues = TenantsQueue::failedQueues();
        $totalFailedJobs = TenantsQueue::totalFailedJobsCount($failedQueues);

        return response()->json([
            'totalJobs' => $totalJobs,
            'totalFailedJobs' => $totalFailedJobs,
            'totalQueues' => count($queues),
            'processedJobsInPastMinute' => TenantsQueue::processedJobsInPastMinutes($queues, 1),
            'processedJobsInPast20Minutes' => TenantsQueue::processedJobsInPastMinutes($queues, 20),
            'processedJobsInPastHour' => TenantsQueue::processedJobsInPastMinutes($queues, 60),
        ]);
    }

}
