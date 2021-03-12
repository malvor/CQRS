# Simple CQRS PHP example

# Installation

Clone repository.

Run docker


1) Type `docker-compose up -d`
0) Open PhpStorm settings
0) Open `Build, Execution, Deployment` -> `Deployment`
0) Click plus icon
0) Choice `SFTP`
0) Set name eg. `cqrs`
0) Set variables
    * Host: `127.0.0.1`
    * Port: `3322`
    * User name: www-data
    * Password: password
    * Check `Save password`
0) Change tab to `mappings` and paste in field `Deployment path` -> `/var/www/cqrs`
0) Open `Build, Execution, Deployment` -> `Deployment` -> `Options`
0) Select `Upload changed files automatically to the default server` -> `Always`
0) Close `Settings`
0) Right click on project dir and click `Deployment` -> `Upload to zadanie`
0) Type in browser `http://127.0.0.1:8990`
0) Type `docker-compose exec php-fpm bash`
0) Type `composer install`

# Create new Command #
E.g. CreateUser.

1) Add new file in `/src/application/Command` called `CreateUser.php`
1) Build command fields and constructor
1) Add new command handler `/src/application/Command/CreateUserHandler.php`
1) Registry command handler as service in `/config/services.xml`
```xml
<service id="App\Application\Command\CreateUserHandler" class="App\Application\Command\CreateUserHandler">
    <argument type="service" id="app.repository.user" />
    <tag name="command_handler" handles="App\Application\Command\CreateUser" />
</service>
```

# Run command #
```php
<?php
use App\Infrastructure\Repository\UserRepositoryInMemory;
use App\Application\CommandBus;
use App\Application\Command\DeleteUser;
use App\Application\Command\DeleteUserHandler;

$repository = new UserRepositoryInMemory();
$commandBus = new CommandBus();
$commandBus->registerHandler(
    DeleteUser::class,
    new DeleteUserHandler($repository)
);
```
