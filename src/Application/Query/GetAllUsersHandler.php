<?php
declare(strict_types=1);

namespace App\Application\Query;

use App\Infrastructure\Repository\UserRepositoryInterface;

final class GetAllUsersHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetAllUsers $query): array
    {
        return $this->userRepository->findAllUsers();
    }
}
