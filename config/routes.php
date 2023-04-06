<?php
declare(strict_types=1);

use App\Controller\IndexController;
use Slim\App;
// Routes
return function (App $app) {
    $c = $app->getContainer();

//    $app->get('/api', IndexController::class . ':indexAction');



    $app->group(
        '/api',
        function (\Slim\Routing\RouteCollectorProxy $group) use ($c) {
            $group->get('/timers', \App\Controller\TimerController::class . ':indexAction');
            $group->post('/timers', \App\Controller\TimerController::class . ':createAction');
            $group->put('/timers/{id}', \App\Controller\TimerController::class . ':updateAction');
            $group->delete('/timers/{id}', \App\Controller\TimerController::class . ':deleteAction');
        });
};