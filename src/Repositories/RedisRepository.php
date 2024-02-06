<?php

namespace Aloware\TenantsQueue\Repositories;

use Aloware\TenantsQueue\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\Redis;

class RedisRepository implements RepositoryInterface
{
    use RedisKeys;

    public $redis;
    public $signals_redis;

    public function __construct()
    {
        $this->redis = $this->getConnection();
    }

    /**
     * Add tenant name to the list
     *
     * @param string $tenant
     *
     * @return void
     */
    public function addTenantNameToTheList($tenant)
    {
        $listKeyName = $this->queueTenantsListKeyName();

        $this->redis->sadd($listKeyName, $tenant);
    }

    /**
     * Check if tenent name already exist
     *
     * @param string $tenant
     *
     * @return void
     */
    public function checkIfTenantNameAlreadyExist($tenant)
    {
        $listKeyName = $this->queueTenantsListKeyName();

        $this->redis->sismember($listKeyName, $tenant);
    }

    /**
     * Get tenant names list
     *
     * @return string
     */
    public function getTenantsName()
    {
        $listKeyName = $this->queueTenantsListKeyName();

        return $this->redis->smembers($listKeyName);
    }

    /**
     * Get random tenant name
     *
     * @return string
     */
    public function getRandomTenantName()
    {
        $listKeyName = $this->queueTenantsListKeyName();

        return $this->redis->srandmember($listKeyName);
    }

    /**
     * Removes tenant name from tenants list
     * @param string $tenant
     *
     * @return void
    */
    public function removeTenantNameFromList($tenant)
    {
        $listKeyName = $this->queueTenantsListKeyName();
        $this->redis->srem($listKeyName, $tenant);
    }

    /**
     * Returns Redis Connection
     *
     * @return \Illuminate\Redis\Connections\Connection
    */
    public function getConnection()
    {
        $database = config('tenants-queue.database');
        return Redis::connection($database);
    }

}
