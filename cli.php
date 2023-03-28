<?php

use App\System\Handlers\HttpErrorHandler;
use App\System\Handlers\ShutdownHandler;
use App\System\ResponseEmitter\ResponseEmitter;
use Psr\Http\Message\UriInterface;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Psr7\Cookies;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UriFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
define('APP_PATH', __DIR__ . '/app');
define('ROOT_PATH', __DIR__);

require ROOT_PATH . '/vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new \DI\ContainerBuilder();

if (false) { // Should be set to true in production
    $containerBuilder->enableCompilation(ROOT_PATH . '/var/cache');
}

// Convert $argv to PATH_INFO and mock console environment
$argv = $GLOBALS['argv'];
array_shift($argv);

$params = [];
for ($i = 2; $i < count($argv); ++$i) {
    if (strpos($argv[$i], '=') !== false) {
        [$key, $value] = explode("=", $argv[$i], 2);
    } else {
        $key = $argv[$i];
        $value = true;
    }

    if (strpos($key, '--') === 0) {
        $key = substr($key, 2);
    }

    $params[$key] = $value;
}

$serverParams = [
    'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
    'REQUEST_URI' => count($argv) >= 2 ? "/{$argv[0]}/{$argv[1]}" : "/help",
    'QUERY_STRING' => http_build_query($params),
];

// Set up settings
$settings = require ROOT_PATH . '/config/settings.php';
$containerBuilder->addDefinitions($settings);

$method = 'GET';
$uri = (new UriFactory())->createFromGlobals($serverParams);
$body = (new StreamFactory())->createStream();
$headers = new Headers();
$cookies = [];
$request = new Request($method, $uri, $headers, $cookies, $serverParams, $body);

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
$routes = require ROOT_PATH . '/config/cli-routes.php';
$routes($app);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

$app->run($request);