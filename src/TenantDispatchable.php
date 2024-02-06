<?php

namespace Aloware\TenantsQueue;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Fluent;
use Closure;

trait TenantDispatchable
{
    use Dispatchable;

    public $tenant;
    /**
     * Dispatch the job with the given arguments.
     *
     * @param  mixed  ...$arguments
     * @return TenantPendingDispatch
     */
    public static function dispatch(...$arguments)
    {
        return new TenantPendingDispatch(new static(...$arguments));
    }

    /**
     * Dispatch the job with the given arguments if the given truth test passes.
     *
     * @param  bool|\Closure  $boolean
     * @param  mixed  ...$arguments
     * @return \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent
     */
    public static function dispatchIf($boolean, ...$arguments)
    {
        if ($boolean instanceof Closure) {
            $dispatchable = new static(...$arguments);

            return value($boolean, $dispatchable)
                ? new TenantPendingDispatch($dispatchable)
                : new Fluent;
        }

        return value($boolean)
            ? new TenantPendingDispatch(new static(...$arguments))
            : new Fluent;
    }

    /**
     * Dispatch the job with the given arguments unless the given truth test passes.
     *
     * @param  bool|\Closure  $boolean
     * @param  mixed  ...$arguments
     * @return \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent
     */
    public static function dispatchUnless($boolean, ...$arguments)
    {
        if ($boolean instanceof Closure) {
            $dispatchable = new static(...$arguments);

            return ! value($boolean, $dispatchable)
                ? new TenantPendingDispatch($dispatchable)
                : new Fluent;
        }

        return ! value($boolean)
            ? new TenantPendingDispatch(new static(...$arguments))
            : new Fluent;
    }
}
