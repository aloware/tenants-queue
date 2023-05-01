<?php
namespace Aloware\TenantsQueue\Facades;

use Illuminate\Support\Facades\Facade;

class TenantsQueue extends Facade {
    protected static function getFacadeAccessor()
    {
        return static::class;
    }

    public static function shouldProxyTo($class)
    {
        app()->singleton(self::getFacadeAccessor(), $class);
    }
}