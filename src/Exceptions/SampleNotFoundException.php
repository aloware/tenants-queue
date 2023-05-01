<?php

namespace Aloware\TenantsQueue\Exceptions;

class SampleNotFoundException extends TenantsQueueException
{
    public function __construct($queue)
    {
        parent::__construct("no sample signal found for the queue '$queue'");
    }
}
