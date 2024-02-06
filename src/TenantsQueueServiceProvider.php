<?php

namespace Aloware\TenantsQueue;

use Aloware\TenantsQueue\Commands\RefreshStats;
use Aloware\TenantsQueue\Commands\TenantsQueueWorker;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Aloware\TenantsQueue\Extensions\TenantsWorker;
use Aloware\TenantsQueue\Facades\TenantsQueue;
use Aloware\TenantsQueue\Repositories\RedisRepository;
use Aloware\TenantsQueue\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\Queue;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;

class TenantsQueueServiceProvider extends ServiceProvider
{
    use RedisRepository;
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
            TenantsQueueWorker::class,
        ]);

        $this->pickJobFromTenants();

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

    /**
     * Pick a job from tenants
     *
     * @return void
     */
    protected function pickJobFromTenants()
    {
        $worker_name = config('tenants-queue.default_worker_name', 'default');
        $tenant = $this->getRandomTenantName();
        Queue::popUsing($worker_name, function ($pop) use($tenant) {
            if(! is_null($job = $pop($tenant))) {
                return $job;
            }
            return;
        });
    }
}
