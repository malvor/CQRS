<?php
declare(strict_types=1);

namespace App\Infrastructure\ViewModel;

use Webmozart\Assert\Assert;

class User
{
    public int $id;

    public string $username;

    public float $points;

    private function __construct(int $id, string $username, float $points)
    {
        $this->id = $id;
        $this->username = $username;
        $this->points = $points;
    }

    public static function fromArray(array $data): self
    {
        Assert::keyExists($data, 'id');
        Assert::keyExists($data, 'username');
        Assert::keyExists($data, 'points');
        return new self((int)$data['id'], (string)$data['username'], (float)$data['points']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'points' => $this->points
        ];
    }
}
