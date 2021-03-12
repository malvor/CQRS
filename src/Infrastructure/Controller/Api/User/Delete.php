<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Command\DeleteUser;
use App\Application\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Delete
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(int $userId)
    {
        try {
            $this->commandBus->handle(new DeleteUser($userId));
        } catch (\Exception $exception) {
            throw $exception;
        }
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
