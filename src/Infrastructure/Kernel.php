<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\CommandBusInterface;
use App\Application\QueryBusInterface;
use App\Infrastructure\Controller\Api\User\Delete;
use App\Infrastructure\Controller\Api\User\Get;
use App\Infrastructure\Controller\Api\User\GetOneByUsername;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;
use \ReflectionMethod;
use \InvalidArgumentException;

final class Kernel
{
    private ContainerInterface $container;

    private RouteCollection $routes;

    private string
        $commandTagName = 'command_handler',
        $queryTagName = 'query_handler';

    public function __construct()
    {
        $this->routes = new RouteCollection();
        $this->build();
        $this->bindRoutes();
    }

    public function handle(Request $request)
    {
        $response = null;
        $context = new RequestContext('/', $request->getMethod());
        $matcher = new UrlMatcher($this->routes, $context);
        try {
            $parameters = $matcher->matchRequest($request);
            Assert::keyExists($parameters, '_controller');
            $controller = $this->container->get($parameters['_controller']);
            $invokeMethod = new ReflectionMethod($parameters['_controller'], '__invoke');
            $matchedParameters = $this->resolveParameters($invokeMethod, $parameters);
            $response = $invokeMethod->invokeArgs($controller, $matchedParameters);
        } catch (\Exception $e) {
            return new JsonResponse([
                'type' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
        return $response;
    }

    private function bindRoutes()
    {
        $this->routes->add('api_users.get', new Route('/api/users', ['_controller' => Get::class], [], [], '', [], Request::METHOD_GET));
        $this->routes->add('api_users.get_one', new Route('/api/users/{username}', ['_controller' => GetOneByUsername::class], [], [], '', [], Request::METHOD_GET));
        $this->routes->add('api_users.delete', new Route('/api/users/{userId}', ['_controller' => Delete::class], [], [], "", [], Request::METHOD_DELETE));
    }

    private function build()
    {
        $containerBuilder = new ContainerBuilder();
        $loader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__));
        $loader->load('../../config/services.xml');
        $this->process($containerBuilder);
        $this->container = $containerBuilder;
    }

    private function process(ContainerBuilder $container)
    {
        /** @var CommandBusInterface $commandBus */
        $commandBus = $container->get('app.command_bus');
        foreach ($container->findTaggedServiceIds($this->commandTagName) as $serviceId => $tags) {
            foreach ($tags as $tagAttributes) {
                $commandBus->registerHandler($tagAttributes['handles'], $container->get($serviceId));
            }
        }
        /** @var QueryBusInterface $queryBus */
        $queryBus = $container->get('app.query_bus');
        foreach ($container->findTaggedServiceIds($this->queryTagName) as $serviceId => $tags) {
            foreach ($tags as $tagAttributes) {
                $queryBus->registerHandler($tagAttributes['handles'], $container->get($serviceId));
            }
        }
    }

    private function resolveParameters(ReflectionMethod $invokeMethod, array $parameters): array
    {
        $matchedParameters = [];
        foreach ($invokeMethod->getParameters() as $parameter) {
            if (isset($parameters[$parameter->getName()])) {
                $matchedParameters[$parameter->getPosition()] = $parameters[$parameter->getName()];
                continue;
            }
            throw new InvalidArgumentException(sprintf('Missing %s argument', $parameter->getName()));
        }
        return $matchedParameters;
    }
}
