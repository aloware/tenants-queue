<?php

namespace Aloware\TenantsQueue\Http\Controllers;

class JobMetricsController extends Controller
{
    /**
     * Get the key performance stats for the dashboard.
     *
     * @return array
     */
    public function index()
    {
       return response()->json(['data' => 0]);
    }

}
