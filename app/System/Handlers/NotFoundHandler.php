<?php


namespace App\System\Handlers;



use Psr\Http\Message\ResponseInterface as Response;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;

class NotFoundHandler extends SlimErrorHandler
{

    use ErrorHandlerLoggerTrait;
    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $response = $this->responseFactory->createResponse(404);
        $response->getBody()->write('404 NOT FOUND');

        return $response;
    }


}