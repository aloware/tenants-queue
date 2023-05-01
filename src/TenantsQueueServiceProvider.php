<?php

namespace Aloware\TenantsQueue;

use Aloware\TenantsQueue\Commands\RefreshStats;
use Aloware\TenantsQueue\Commands\CustomQueueWorker;
use Aloware\TenantsQueue\Commands\TenantsQueueWorker;
use Aloware\TenantsQueue\Extensions\TenantsWorker;
use Aloware\TenantsQueue\Facades\TenantsQueue;
use Aloware\TenantsQueue\Repositories\RedisRepository;
use Aloware\TenantsQueue\Interfaces\RepositoryInterface;
use Aloware\TenantsQueue\Repositories\RedisKeys;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;

class TenantsQueueServiceProvider extends ServiceProvider
{
    use RedisKeys;
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerTenantsWorker();

        // register TenantsQueueWorker
        $this->app->singleton(TenantsQueueWorker::class, function ($app) {
            return new TenantsQueueWorker($app['queue.tenants_worker'], $app['cache.store']);
        });
        $this->app->singleton(
            RepositoryInterface::class,
            RedisRepository::class
        );
        TenantsQueue::shouldProxyTo(RepositoryInterface::class);
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->commands([
            RefreshStats::class,
            CustomQueueWorker::class,
        ]);

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            if(config('tenants-queue.stats.enabled')) {
                // refresh stats for dashboard
                $schedule->command(RefreshStats::class)->everyMinute()->withoutOverlapping();
            }
        });
    }

    /**
     * Register the tenants queue worker.
     *
     * @return void
     */
    protected function registerTenantsWorker()
    {
        $this->app->singleton('queue.tenants_worker', function ($app) {
            $isDownForMaintenance = function () {
                return $this->app->isDownForMaintenance();
            };

            $resetScope = function () use ($app) {
                if (method_exists($app['log']->driver(), 'withoutContext')) {
                    $app['log']->withoutContext();
                }

                if (method_exists($app['db'], 'getConnections')) {
                    foreach ($app['db']->getConnections() as $connection) {
                        $connection->resetTotalQueryDuration();
                        $connection->allowQueryDurationHandlersToRunAgain();
                    }
                }

                $app->forgetScopedInstances();

                return Facade::clearResolvedInstances();
            };

            return new TenantsWorker(
                $app['queue'],
                $app['events'],
                $app[ExceptionHandler::class],
                $isDownForMaintenance,
                $resetScope
            );
        });
    }
}
