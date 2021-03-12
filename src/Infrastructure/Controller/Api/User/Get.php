<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Query\GetAllUsers;
use App\Application\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class Get
{
    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke()
    {
        return new JsonResponse($this->queryBus->ask(
            new GetAllUsers()
        ));
    }
}
