<?php
declare(strict_types=1);

namespace App\Application\Query;

use App\Infrastructure\Repository\UserRepositoryInterface;
use App\Infrastructure\ViewModel\User;

final class FindOneByUsernameHandler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(FindOneByUsername $query): User
    {
        $user = $this->userRepository->findOneByUsername($query->username());
        return User::fromArray($user);
    }
}
