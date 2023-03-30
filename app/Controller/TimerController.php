<?php

namespace App\Controller;

use App\Util\Db;
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
        $userId = 1;
        $db = Db::getDb();
        $date = date('Y-m-d');
//        $db->beginDebug();
        $timers = $db->select('timers', [
            '[>]timer_entries' => [
                'id' => 'timer_id',
                'AND' => ['timer_entries.date' => $date]
            ]
        ],
            [
                'timers.id[Int]',
                'timers.title',
                'timers.length[Int]',
                'timer_entries.elapsed[Int]',
            ],
            ['user_id' => 1]
        );
        foreach ($timers as $key => $timer) {
            if (is_null($timer['elapsed'])) {
                $timers[$key]['elapsed'] = 0;
            }
        }


//        var_dump($db->debugLog());exit;
        $response->getBody()->write(json_encode($timers));
        return $response->withStatus(200);
    }

    public function fetchAction(Request $request, Response $response)
    {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function updateAction(Request $request, Response $response, array $args)
    {
        $db = Db::getDb();
        $id = $args['id'];
        $post = $request->getParsedBody();

        $date = date('Y-m-d');
        $timerEntry = $db->get('timer_entries', '*',
            [
                'timer_id' => $id,
                'date' => $date
            ]
        );

        if ($timerEntry) {
            $db->update('timer_entries', ['elapsed' => $post['elapsed']], ['id' => $timerEntry['id']]);
        } else {
            $db->insert('timer_entries', [
                'elapsed' => $post['elapsed'],
                'timer_id' => $id,
                'date' => $date
            ]);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function createAction(Request $request, Response $response)
    {
        $userId = 1;
        $post = $request->getParsedBody();
        $db = Db::getDb();
        $db->insert('timers', [
            'user_id' => $userId,
            'title' => $post['title'],
            'length' => $post['length'],
            'date_created' => date('Y-m-d H:i:s'),
        ]);

        $response->getBody()->write(json_encode(['id' => $db->id()]));
        return $response->withStatus(201);
    }

    public function deleteAction(Request $request, Response $response)
    {

    }

}