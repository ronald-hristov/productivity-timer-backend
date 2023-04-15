<?php
declare(strict_types=1);

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'db' => function ($c) {
            /** @var \Psr\Container\ContainerInterface $c */
            $settings = $c->get('settings')['db'];
            $db = new \Medoo\Medoo($settings);
            unset($settings);
            return $db;
        },
        'logger' => function ($c) {
            $loggerService = new \App\System\Logger();
            return $loggerService();
        },
        'view' => function ($c) {
            $phpView = new \Slim\Views\PhpRenderer(ROOT_PATH . '/view/');
            $phpView->setLayout('layout.phtml');

            return $phpView;
        },
        'session' => function ($c) {return new \SlimSession\Helper;},
        \App\Middleware\UserAwareRequestMiddleware::class => function ($c) {return \App\Factory\UserAwareRequestMiddlewareFactory::create($c);},
        \App\Middleware\PermissionsMiddleware::class => function ($c) {return \App\Factory\PermissionsMiddlewareFactory::create($c);},
        \Slim\Middleware\Session::class => function ($c) {return \App\Factory\SessionMiddlewareFactory::create($c);},
        \App\Service\Auth::class => function ($c) {
            $user = $c->get('session')->get('user');
            return new \App\Service\Auth($user);
        },


    ]);
};

