<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Models\Servers;
use Source\Models\Times;


class Web
{
    private Engine $view;
    public function __construct()
    {
        $session = new Session();
        $user = new User();
        $this->view = Engine::create(__DIR__ . "/../../themes", "php");
        if($session->has('user')) {
            $this->view->addData(['user' => $user]);
        }
    }

    public function home()
    {
        echo $this->view->render("home", [
            'title' => 'Server-List',
            'servers' => (new Servers())->find()->limit(6)->order("votes DESC")->fetch(true)
        ]);

    }

    public function profile()
    {
        $servers = new Servers();
        $session = new Session();
        $user = new User();
        if($session->has('user')){
            echo $this->view->render("profile", [
                'title' => 'Server-List | ' . $user->getName(),
                'guilds' => $_SESSION['guilds'],
                'servers' => $servers->serversIds(),
                'profile' => true
            ]);
        }else {
            echo 'você não está logado ;-;';
        }
    }

    public function login($data)
    {
        $authorizeURL = 'https://discord.com/api/oauth2/authorize';
        $tokenURL = 'https://discord.com/api/oauth2/token';
        $apiURLBase = 'https://discord.com/api/users/@me';

        // Start the login process by sending the user to Discord's authorization page
        if (get('action') == 'login') {

            $params = array(
                'client_id' => OAUTH2_CLIENT_ID,
                'redirect_uri' => URL_BASE.'/login',
                'response_type' => 'code',
                'scope' => 'identify guilds'
            );

            // Redirect the user to Discord's authorization page
            header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
            die();
        }

        // When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
        if (get('code')) {

            // Exchange the auth code for a token
            $token = apiRequest($tokenURL, array(
                "grant_type" => "authorization_code",
                'client_id' => OAUTH2_CLIENT_ID,
                'client_secret' => OAUTH2_CLIENT_SECRET,
                'redirect_uri' => URL_BASE.'/login',
                'code' => get('code')
            ));
            $logout_token = $token->access_token;
            $_SESSION['access_token'] = $token->access_token;


            header('Location: ' . $_SERVER['PHP_SELF']);
        }

        if (session('access_token')) {
            $session = new Session();
            $user = apiRequest($apiURLBase);
            $guilds = apiRequest('https://discord.com/api/users/@me/guilds');

            $session->set('user', $user);
            $session->set('guilds', $guilds);

            header('Location: '. url());
            die();
        } else {
            header('Location: '. url('login?action=login'));
            die();
        }

    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . URL_BASE);
    }

    public function server($data)
    {
        $urlId = $data['serverId'];
        if(strlen($urlId) < 18) {
            $this->error([
                'errcode' => '404'
            ]);
            die();
        } else {
            $time = new \DateTime();
            $time = $time->add(new \DateInterval('PT12H'));

            $servers = new Servers();
            if(in_array($urlId, $servers->serversIds())) {
                $server = $servers->find('server_id = :sId','sId='.$urlId)->fetch();
                echo $this->view->render('servers',[
                    'title' => 'Server-List | ' . $server->name,
                    'server' => $server,
                    'time' => $time->format('c')
                ]);
            } else {
                echo 'Servidor Não encontrado...';
            }
        }
    }

    public function vote($data)
    {
        if(strlen($data['serverId']) <> 18) {
            $this->error([
                'errcode' => '404'
            ]);
            die();
        }
        $servers = (new Servers())->serversIds();
        if(!in_array($data['serverId'], $servers)) {
            $this->error([
                'errcode' => '404'
            ]);
            die();
        }
        $session = new Session();
        if(!$session->has('user')) {
            header('Location: ' . url('login'));
            die();
        }

        $times = new Times();
        $result = $times->getTime($session->user->id, $data['serverId']);
        if($result) {
            //$userTime = new \DateTime('2020-10-05T04:05:18.134-03:00');
            $userTime = new \DateTime($result->time);
            $now = new \DateTime();
            $server = (new Servers())->find('server_id = :sId','sId='.$data['serverId'])->fetch();
            if($now >= $userTime) {
                $server->votes +=1;
                $server->save();
                $newTime = (new Times())->findById($result->id);
                $newTime->time = $now->add(new \DateInterval('PT12H'))->format('c');
                $newTime->updatedAt = date('Y-m-d H:i:s');
                $newTime->save();
                echo $this->view->render('vote-page', [
                    'title' => 'Server-list |'. $server->name,
                    'canVote' => true,
                    'timer' => $newTime->time,
                    'serverName' => $server->name,
                    'votes' => $server->votes
                ]);
            } else {
                echo $this->view->render('vote-page', [
                    'title' => 'Server-List |'. $server->name,
                    'canVote' => false,
                    'timer' => $userTime->format('c')
                ]);
            }
        } else {
            //echo 'nada encontrado';
            $now = new \DateTime();
            $newTime = new Times();
            $server = (new Servers())->findById($data['serverId']);
            $server->votes +=1;
            $server->save();

            $newTime->user_id = $session->user->id;
            $newTime->server_id = $data['serverId'];
            $newTime->time = $now->add(new \DateInterval('PT12H'))->format('c');
            $newTime->createdAt=date('Y-m-d H:i:s');
            $newTime->updatedAt=date('Y-m-d H:i:s');
            $newTime->save();

            vd($newTime);
            vd($server);
        }

    }

    public function join($data)
    {
        $urlId = $data['serverId'];
        $servers = new Servers();
        $server = $servers->find("server_id = :sId", "sId=". $urlId)->fetch();
        vd($server->invite);
    }

    public function error($data)
    {
        if($data['errcode'] === '404') {
            echo 'página não encontrada :(';
        }
    }

}
