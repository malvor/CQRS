<?php
declare(strict_types=1);

namespace App\Application\Command;

final class AddUser
{
    private string $username;

    private float $points;

    public function __construct(string $username, float $points)
    {
        $this->username = $username;
        $this->points = $points;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function points(): float
    {
        return $this->points;
    }
}
