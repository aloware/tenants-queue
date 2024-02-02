# Aloware - Tenants Queue

Laravel package to provide fair consumption of jobs against multiple tenants.

## Installation
```sh
composer require aloware/tenants-queue
```

## Usage
This package uses Redis as data storage. By default it uses `default`
redis connection. You may configure to use another connection within
the tenants-queue config file or by setting in the environment file.

```
TENANTS_QUEUE_REDIS_DB="default"
TENANTS_QUEUE_KEY_PREFIX="tenants-queue"
```

Now, you need to replace `use Dispatchable;` with `use TenantDispatchable;`
in the Job class you need fair consumption functionality.
```
<?php

namespace App\Jobs;

use Aloware\TenantsQueue\TenantDispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExampleJob implements ShouldQueue
{
    use TenantDispatchable, InteractsWithQueue, Queueable, SerializesModels;
...
```

You can partition your data using `->tenant()` at dispatch time
and let your queue jobs be consumed fairly among those partitions.
```
ExampleJob::dispatch()
    ->onConnection($connection)
    ->onQueue($queue)
    ->tenant();
```

### Retries
That is very important to understand the mechanics of this package.
You may understand that the FairSignalJob which has been sent to the
queue instead of the original job has no idea about the exact job which is
going to be processed after the signal is received by the consumer.

There is no guarantee that the same job will
be selected to be processed by the same signal in case of failure/retry.

So the number of tries you configure on `queue:work` command is not
effective. It is recommended to set it to the biggest number you
can imagine for max tries (ie. 10) and set the number of tries using
the fair queue's `->tries()` chain call.
```
ExampleJob::dispatch()
    ->onConnection($connection)
    ->onQueue($queue)
    ->tenant()
    ->tries(3);
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
