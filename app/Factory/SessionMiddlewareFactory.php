<?php declare(strict_types=1);


namespace App\Factory;


use Slim\Middleware\Session;

class SessionMiddlewareFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $c
     * @return Session
     */
    public static function create(\Psr\Container\ContainerInterface $c): Session
    {
        $sessionSettings = $c->get('settings')['session'];
        return new \Slim\Middleware\Session($sessionSettings);
    }
}