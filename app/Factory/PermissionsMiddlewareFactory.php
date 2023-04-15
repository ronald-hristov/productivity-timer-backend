<?php


namespace App\Factory;


use App\Middleware\PermissionsMiddleware;
use App\Service\Auth;

class PermissionsMiddlewareFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $c
     * @return PermissionsMiddleware
     */
    public static function create(\Psr\Container\ContainerInterface $c): PermissionsMiddleware
    {
        /** @var Auth $auth */
        $auth = $c->get(Auth::class);
        $user = $auth->getCurrentUser();

        return new PermissionsMiddleware($user);
    }
}