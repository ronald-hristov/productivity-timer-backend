<?php
declare(strict_types=1);

use App\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(\App\Middleware\JsonBodyParserMiddleware::class);
    $app->add(\App\Middleware\UserAwareRequestMiddleware::class);
    $app->add($app->getContainer()->get(\Slim\Middleware\Session::class));
    \App\Util\Db::setDb($app->getContainer()->get('db'));
};