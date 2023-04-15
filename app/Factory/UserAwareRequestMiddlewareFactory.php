<?php declare(strict_types=1);


namespace App\Factory;

use App\Middleware\UserAwareRequestMiddleware;
use App\Service\Auth;


class UserAwareRequestMiddlewareFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $c
     * @return UserAwareRequestMiddleware
     */
    public static function create(\Psr\Container\ContainerInterface $c): UserAwareRequestMiddleware
    {
        /** @var Auth $auth */
        $auth = $c->get(Auth::class);
        $user = $auth->getCurrentUser();

        return new UserAwareRequestMiddleware($user);
    }
}