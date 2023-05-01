<?php

namespace Aloware\TenantsQueue\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Get the key performance stats for the dashboard.
     *
     * @return array
     */
    public function index()
    {
        return view('tenantsqueue::layout', [
            'cssFile' => 'app.css',
            'tenantsqueueScriptVariables' => ['path' => 'tenantsqueue']
        ]);
    }

}
