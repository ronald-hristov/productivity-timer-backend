<?php
declare(strict_types=1);

use Slim\App;

// Cli Routes
return function (App $app) {
    $app->get('/{command}/{method}', \App\Controller\CliController::class . ':process');
};