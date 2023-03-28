<?php
declare(strict_types=1);

use App\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $sessionSettings = $app->getContainer()->get('settings')['session'];
    $app->add(new \Slim\Middleware\Session($sessionSettings));
//    \App\Util\Db::setDb($app->getContainer()->get('db'));
};