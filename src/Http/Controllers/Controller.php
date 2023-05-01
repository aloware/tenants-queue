<?php

namespace Aloware\TenantsQueue\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Laravel\Horizon\Http\Middleware\Authenticate;

class Controller extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }
}
