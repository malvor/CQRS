<?php
declare(strict_types=1);

namespace App\Application;

interface QueryBusInterface
{
    public function registerHandler(string $queryClass, object $handler): void;
    public function ask(object $query);
}
