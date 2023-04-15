<?php


namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use SlimSession\Helper;

class UserAwareRequestMiddleware implements Middleware
{
    /**
     * @var array|null
     */
    protected ?array $user;

    /**
     * PermissionsMiddleware constructor.
     * @param array|null $user
     */
    public function __construct(?array $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute('user', $this->user);
        return $handler->handle($request);
    }
}