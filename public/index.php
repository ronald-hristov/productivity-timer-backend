<?php
declare(strict_types=1);

use App\System\Handlers\HttpErrorHandler;
use App\System\Handlers\ShutdownHandler;
use App\System\ResponseEmitter\ResponseEmitter;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

define('APP_PATH', __DIR__ . '/../app');
define('ROOT_PATH', __DIR__ . '/..');
define('DATA_PATH', __DIR__ . '/../app/data');

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require ROOT_PATH . '/vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) { // Should be set to true in production
    $containerBuilder->enableCompilation(ROOT_PATH . '/var/cache');
}

// Set up settings
$settings = require ROOT_PATH . '/config/settings.php';
$containerBuilder->addDefinitions($settings);

// Set up dependencies
$dependencies = require ROOT_PATH . '/config/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
//$repositories = require ROOT_PATH . '/app/config/repositories.php';
//$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require ROOT_PATH . '/config/middleware.php';
$middleware($app);

// Register routes
$routes = require ROOT_PATH . '/config/routes.php';
$routes($app);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$logger = $container->get('logger');
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $logger);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
$errorMiddleware->setErrorHandler(\Slim\Exception\HttpNotFoundException::class, new \App\System\Handlers\NotFoundHandler($callableResolver, $responseFactory, $logger));

//// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);




