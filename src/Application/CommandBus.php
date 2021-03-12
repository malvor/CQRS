<?php
declare(strict_types=1);

namespace App\Application;

use Webmozart\Assert\Assert;

final class CommandBus implements CommandBusInterface
{
    use BusTrait;

    public function handle(object $command): void
    {
        Assert::object($command);
        Assert::keyExists($this->handlers, get_class($command));

        $this->handlers[get_class($command)]($command);
    }
}
