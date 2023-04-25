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
            $group->get('/timers', \App\Controller\TimerController::class . ':indexAction')->add(\App\Middleware\PermissionsMiddleware::class);
            $group->post('/timers', \App\Controller\TimerController::class . ':createAction')->add(\App\Middleware\PermissionsMiddleware::class);
            $group->post('/timers/{id}/complete', \App\Controller\TimerController::class . ':completeAction')->add(\App\Middleware\PermissionsMiddleware::class);
            $group->put('/timers/{id}', \App\Controller\TimerController::class . ':updateAction')->add(\App\Middleware\PermissionsMiddleware::class);
            $group->post('/timers/{id}/time', \App\Controller\TimerController::class . ':addTimeElapsedAction')->add(\App\Middleware\PermissionsMiddleware::class);
            $group->delete('/timers/{id}', \App\Controller\TimerController::class . ':deleteAction')->add(\App\Middleware\PermissionsMiddleware::class);
            $group->post('/auth/login', \App\Controller\AuthController::class . ':loginAction');
            $group->get('/auth/logout', \App\Controller\AuthController::class . ':logoutAction');
        });
};