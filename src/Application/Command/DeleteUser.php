<?php
declare(strict_types=1);

namespace App\Application\Command;

final class DeleteUser
{
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function userId(): int
    {
        return $this->userId;
    }
}
