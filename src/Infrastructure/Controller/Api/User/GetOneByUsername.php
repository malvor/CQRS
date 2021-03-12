<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Query\FindOneByUsername;
use App\Application\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetOneByUsername
{
    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(string $username)
    {
        return new JsonResponse($this->queryBus->ask(
            new FindOneByUsername($username)
        ));
    }
}
