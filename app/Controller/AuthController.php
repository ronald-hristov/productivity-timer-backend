<?php

namespace App\Controller;

use App\Util\Db;
use Beste\Clock\SystemClock;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Kreait\Firebase\JWT\Error\IdTokenVerificationFailed;
use Kreait\Firebase\JWT\IdTokenVerifier;
use SlimSession\Helper;

class AuthController extends AbstractController
{
    /**
     * Home page
     *
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface|Response
     */
    public function loginAction(Request $request, Response $response, $args)
    {
        $post = $request->getParsedBody();
        $projectId = $this->__get('settings')['firebase']['project_id'];
        $idToken = $post['accessToken']; // An ID token given to your backend by a Client application
        $verifier = IdTokenVerifier::createWithProjectId($projectId);

        try {
            $token = $verifier->verifyIdToken($idToken);
            // check for existing user with email
            $db = Db::getDb();
            $email = $token->payload()['email'];
            $user = $db->get('users', '*', ['email' => $email,]);

            // create user if email doesn't exist
            if (!$user) {
                $name = $token->payload()['name'];
                $db->insert('users', [
                    'email' => $email,
                    'name' => $name,
                    'date_created' => date('Y-m-d H:i:s')
                ]);
                $user = $db->get('users', '*', ['email' => $email,]);
            }

            // add user to session
            /** @var Helper $session */
            $session = $this->__get('session');
            $session->set('user', $user);

            $response->getBody()->write(json_encode($_SESSION));

        } catch (IdTokenVerificationFailed $e) {
            // TODO add 400 code
            $response->withStatus(401)->getBody()->write(json_encode(['error' => $e->getMessage()]));
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function logoutAction(Request $request, Response $response, $args)
    {
        $session = new Helper();
        $session->delete('user');

        return $response;
    }

}