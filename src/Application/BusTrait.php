<?php
declare(strict_types=1);

namespace App\Application;

use Webmozart\Assert\Assert;

trait BusTrait
{
    /** @var array<object>  */
    private array $handlers = [];

    public function registerHandler(string $commandClass, object $handler): void
    {
        Assert::object($handler);
        Assert::methodExists($handler, '__invoke');

        $this->handlers[$commandClass] = $handler;
    }
}
