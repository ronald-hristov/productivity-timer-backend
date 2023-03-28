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

    ]);
};

