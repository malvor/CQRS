<?php
declare(strict_types=1);
use \Symfony\Component\HttpFoundation\Request;
use App\Infrastructure\Kernel;
require __DIR__ . '/../vendor/autoload.php';

$kernel = new Kernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
