<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use App\Middleware\StaticFilesMiddleware;

$app = AppFactory::create();
$app->addRoutingMiddleware();

// Middleware-ul pentru fișiere statice
$app->add(new StaticFilesMiddleware(__DIR__));

$app->add(new MethodOverrideMiddleware());

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/routes.php';

$app->run();
