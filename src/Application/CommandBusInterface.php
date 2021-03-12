<?php
declare(strict_types=1);

namespace App\Application;

interface CommandBusInterface
{
    public function registerHandler(string $commandClass, object $handler): void;
    public function handle(object $command): void;
}
