<?php

namespace App\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CliController extends AbstractController
{
    /**
     * Processes cli requests
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Exception
     */
    public function process(Request $request, Response $response)
    {
        try {
            $response->withHeader('Content-Type', 'text/plain');
            global $argv;
            $command = array_shift($argv);
            $method = array_shift($argv);

            $class = '\\App\\Console\\' . ucfirst($command);
            if (!class_exists($class)) {
                throw new \Exception('Class ' . $class . ' does not exist');
            }
            $object = new $class($this->container);

            if (!method_exists($object, $method)) {
                throw new \Exception(sprintf('Method "%s" does not exist in class "%s"', $method, $class));
            }
            $params = $request->getQueryParams();

            $result = $object->$method($params);
            $response->getBody()->write($result . "\n");
            return $response;
        } catch (\Throwable $e) {
            echo $e->getMessage() . "\n";
            exit;
        }
    }
}