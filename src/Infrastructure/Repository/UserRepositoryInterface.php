<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\ViewModel\User as UserView;

interface UserRepositoryInterface
{
    public function findAllUsers(): array;

    public function findOneByUsername(string $username): array;

    public function getUser(int $userId): array;

    public function deleteUser(int $userId): void;
}
