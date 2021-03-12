<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Webmozart\Assert\Assert;

final class UserInFileRepository implements UserRepositoryInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function findAllUsers(): array
    {
        $content = json_decode(file_get_contents(__DIR__ . '/' . $this->filePath), true, 512, JSON_ERROR_DEPTH);
        return $content;
    }

    public function deleteUser(int $userId): void
    {
        $users = $this->findAllUsers();
        foreach ($users as $key => $user) {
            if ($user['id'] === $userId) {
                unset($users[$key]);
            }
        }
        $this->saveStructure($users);
    }

    public function findOneByUsername(string $username): array
    {
        return $this->findOneUserByField('username', $username);
    }

    public function getUser(int $userId): array
    {
        return $this->findOneUserByField('id', $userId);
    }

    private function findOneUserByField(string $field, $value): array
    {
        $users = $this->findAllUsers();
        $user = array_filter($users, function($user) use ($field, $value) {
            return $user[$field] === $value;
        });
        Assert::eq(count($user), 1);
        return reset($user);
    }

    private function saveStructure(array $users): void
    {
        file_put_contents(__DIR__ . '/' . $this->filePath, json_encode($users));
    }
}
