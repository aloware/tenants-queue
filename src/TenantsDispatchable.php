<?php

namespace Aloware\TenantsQueue;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Fluent;

trait TenantsDispatchable
{
    use Dispatchable;

    public static function dispatch(...$arguments)
    {

        return new PendingDispatch($job);
    }

    public static function dispatchIf($boolean, ...$arguments)
    {
        return $boolean ? new PendingDispatch($job) : new Fluent;
    }

    public static function dispatchUnless($boolean, ...$arguments)
    {
        return !$boolean ? new PendingDispatch($job) : new Fluent;
    }

    public static function dispatchSync(...$arguments)
    {
        return app(Dispatcher::class)->dispatchSync($job);
    }

    public static function dispatchNow(...$arguments)
    {
        return app(Dispatcher::class)->dispatchNow($job);
    }

    public static function dispatchAfterResponse(...$arguments)
    {
        return app(Dispatcher::class)->dispatchAfterResponse($job);
    }

}
