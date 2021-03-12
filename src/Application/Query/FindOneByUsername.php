<?php
declare(strict_types=1);

namespace App\Application\Query;

final class FindOneByUsername
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function username(): string
    {
        return $this->username;
    }
}
