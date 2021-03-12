<?php
declare(strict_types=1);

namespace App\Application;

use Webmozart\Assert\Assert;

class QueryBus implements QueryBusInterface
{
    use BusTrait;

    public function ask($query)
    {
        Assert::object($query);
        Assert::keyExists($this->handlers, get_class($query));

        return $this->handlers[get_class($query)]($query);
    }
}
