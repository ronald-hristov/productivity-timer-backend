<?php
declare(strict_types=1);

use App\Controller\IndexController;
use Slim\App;
// Routes
return function (App $app) {
    $app->get('/', IndexController::class . ':indexAction');
};