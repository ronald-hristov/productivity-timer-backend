<?php

namespace App\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class IndexController extends AbstractController
{
    /**
     * Home page
     *
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface|Response
     */
    public function indexAction(Request $request, Response $response)
    {
        $a = 2 / 0;
//        throw new \Exception('uga buga');
        $response = $this->view->render($response, 'index/index.phtml');
        return $response;
    }

}