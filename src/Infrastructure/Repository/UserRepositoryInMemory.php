<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use \Exception;

final class UserRepositoryInMemory implements UserRepositoryInterface
{
    /** @var array  */
    private array $database = [
        [
            'id' => 1,
            'username' => 'user1',
            'points' => 45.0
        ],
        [
            'id' => 2,
            'username' => 'user2',
            'points' => 97.0
        ],
        [
            'id' => 3,
            'username' => 'user3',
            'points' => 87.0
        ],
        [
            'id' => 5,
            'username' => 'user5',
            'points' => 2.0
        ],
        [
            'id' => 6,
            'username' => 'user6',
            'points' => 5.0
        ]
    ];

    public function findAllUsers(): array
    {
        return $this->database;
    }

    /**
     * @param string $username
     * @return array
     * @throws Exception
     */
    public function findOneByUsername(string $username): array
    {
        $users = array_filter($this->database, function($user) use ($username) {
            return $user['username'] === $username;
        });
        $countUsers = count($users);
        if ($countUsers === 0) {
            throw new Exception('Not found user.');
        }
        if ($countUsers > 1) {
            throw new Exception('Found to many users.');
        }
        return reset($users);
    }

    /**
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getUser(int $userId): array
    {
        $users = array_filter($this->database, function($user) use ($userId) {
            return $user['id'] === $userId;
        });
        $countUsers = count($users);
        if ($countUsers === 0) {
            throw new Exception('Not found user.');
        }
        if ($countUsers > 1) {
            throw new Exception('Found to many users.');
        }
        return reset($users);
    }

    public function deleteUser(int $userId): void
    {
        foreach ($this->database as $key => $user) {
            if ($user['id'] === $userId) {
                unset($this->database[$key]);
            }
        }
    }
}
