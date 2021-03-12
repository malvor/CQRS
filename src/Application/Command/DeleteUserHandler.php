<?php
declare(strict_types=1);

namespace App\Application\Command;

use App\Infrastructure\Repository\UserRepositoryInterface;

final class DeleteUserHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(DeleteUser $command): void
    {
        $this->userRepository->getUser($command->userId());
        $this->userRepository->deleteUser($command->userId());
    }
}
