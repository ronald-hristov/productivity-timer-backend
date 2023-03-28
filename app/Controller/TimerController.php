<?php

namespace App\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class TimerController extends AbstractController
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
        $response = $this->view->render($response, 'index/index.phtml');
        return $response;
    }

    public function fetchAction(Request $request, Response $response)
    {

    }

    public function updateAction(Request $request, Response $response)
    {

    }

    public function createAction(Request $request, Response $response)
    {

    }

    public function deleteAction(Request $request, Response $response)
    {

    }

}