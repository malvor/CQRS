<?php
declare(strict_types=1);

namespace App\Tests\Application;

use App\Infrastructure\Repository\UserRepositoryInMemory;
use PHPUnit\Framework\TestCase;
use \App\Infrastructure\Repository\UserRepositoryInterface;
use \App\Application\{
    CommandBusInterface,
    CommandBus,
    QueryBusInterface,
    QueryBus,
    Command\DeleteUser,
    Command\DeleteUserHandler,
    Query\FindOneByUsername,
    Query\FindOneByUsernameHandler,
    Query\GetAllUsers,
    Query\GetAllUsersHandler
};

final class SystemTest extends TestCase
{
    private UserRepositoryInterface $userRepository;

    private CommandBusInterface $commandBus;

    private QueryBusInterface $queryBus;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepositoryInMemory();
        $this->commandBus = new CommandBus();
        $this->commandBus->registerHandler(
            DeleteUser::class,
            new DeleteUserHandler($this->userRepository)
        );
        $this->queryBus = new QueryBus();
        $this->queryBus->registerHandler(
            GetAllUsers::class,
            new GetAllUsersHandler($this->userRepository)
        );
        $this->queryBus->registerHandler(
            FindOneByUsername::class,
            new FindOneByUsernameHandler($this->userRepository)
        );
    }

    public function testCanDeleteUser(): void
    {
        $users = $this->userRepository->findAllUsers();
        $this->assertEquals(5, count($users));
        $this->assertNotEmpty($this->userRepository->findOneByUsername('user1'));
        $this->commandBus->handle(
            new DeleteUser(1)
        );
        $this->expectException(\Exception::class);
        $this->userRepository->findOneByUsername('user1');
    }
}
