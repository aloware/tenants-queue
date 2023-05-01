<?php

namespace Aloware\TenantsQueue\Exceptions;

class EmptyPartitionNameException extends TenantsQueueException
{
    public function __construct()
    {
        parent::__construct("tenant name cannot be empty");
    }
}
