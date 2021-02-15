<?php

namespace Source\App;


use League\Plates\Engine;
use Source\Models\Servers;
use Source\Models\Tags;
use Source\Models\Times;


class Web
{
    private Engine $view;
    private Servers $servers;
    private User $user;
    private Session $session;
    private Times $times;

    public function __construct()
    {
        $this->session = new Session();
        $this->user = new User();
        $this->servers = new Servers();
        $this->times = new Times();

        $this->view = Engine::create(__DIR__ . "/../../themes", "php");
        if ($this->session->has('user')) {
            $this->view->addData(['user' => $this->user]);
        }
    }

    public function home()
    {
        echo $this->view->render("home", [
            'title' => 'Server-List',
            'servers' => $this->servers->publishes(6),
        ]);
    }

    public function profile()
    {

        if ($this->session->has('user')) {
            $userGuilds = $this->session->guilds;
            echo $this->view->render("profile", [
                'title' => 'Server-List | ' . $this->user->getName(),
                'guilds' => $userGuilds,
                'servers' => $this->servers->findServers($userGuilds),
                'profile' => true
            ]);
        } else {
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
                'redirect_uri' => URL_BASE . '/login',
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
                'redirect_uri' => URL_BASE . '/login',
                'code' => get('code')
            ));
            $logout_token = $token->access_token;
            $_SESSION['access_token'] = $token->access_token;


            header('Location: ' . $_SERVER['PHP_SELF']);
        }

        if (session('access_token')) {
            $user = apiRequest($apiURLBase);
            $guilds = apiRequest('https://discord.com/api/users/@me/guilds');

            $this->session->set('user', $user);
            $this->session->set('guilds', $guilds);

            header('Location: ' . url());
            die();
        } else {
            header('Location: ' . url('login?action=login'));
            die();
        }
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: ' . URL_BASE);
    }

    public function server($data)
    {
        $urlId = $data['serverId'];
        if (!$this->servers->serverExists($urlId)) {
            $this->error([
                'errcode' => '404'
            ]);
            die();
        }

        $time = new \DateTime();
        $time = $time->add(new \DateInterval('PT12H'));

        if ($server = ($this->servers->findServer($urlId))[0]) {
            echo $this->view->render('servers', [
                'title' => 'Server-List | ' . $server->name,
                'server' => $server,
                'time' => $time->format('c'),
                'tags' => (new Tags())->getTags($server->server_id)
            ]);
        } else {
            echo 'Servidor Não encontrado...';
        }
    }

    public function config($data)
    {
        $urlId = $data['serverId'];
        if (!$this->servers->serverExists($urlId)) {
            $this->error([
                'errcode' => '404'
            ]);
            die();
        }
        if (!isLoged($this->session)) {
            header('Location: ' . url('login'));
            die();
        }
        $guilds = (array)$this->session->guilds;
        $guildsIds = [];

        foreach ($guilds as $guild) {
            array_push($guildsIds, $guild->id);
        }

        if (!in_array($urlId, $guildsIds)) {
            echo "você precisa estar no servidor e ser um ADM";
        } else {

            //if($guilds[(array_search($urlId, $guilds))]->permissions == '2147483647')
            $key = array_search($urlId, $guilds);

            if ($guilds[$key]->permissions == '2147483647') {
                echo "você não é AMD";
            }

            echo $this->view->render('config', [
                'title' => 'Server-list | Configurations',
                'server' => $this->servers->findServer($urlId)[0]
            ]);
        }
    }

    public function form($data)
    {
        header("Access-Control-Allow-Origin: *");
        if ($_REQUEST && !csrf_verify($_REQUEST)) {
            echo $this->view->render('config', [
                'title' => 'Server-list | Configurations',
                'server' => $this->servers->findServer($data['serverId']),
                'error' => 'O envio foi bloqueado por medidas de segurança, tente novamente',
            ]);
            die();
        }
        $data = filter_var_array($data, FILTER_DEFAULT);
        $errorMessage = [];
        $invite = $data['invite'];
        $description = $data['description'];

        if (in_array('', $data)) {
            array_push($errorMessage, 2);
        }
        if (strlen($description) > 140) {
            array_push($errorMessage, 3);
        }

        if (strlen($invite) > 7) {
            $invite = substr($invite, 19);
        }

        if ($errorMessage) {
            foreach ($errorMessage as $code) {
                echo $code;
            }
            die();
        }
        /*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://discordapp.com/api/invites/'. $invite);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if(curl_errno($ch))
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        vd($response);
        curl_close($ch);
        if(@$response->code == 10006) {
            echo 'Convite inválido';
        } else {
            echo 'passou';
        }
        */

        $result = $this->servers->configServer(
            $data['serverId'],
            [
                'invite' => $invite,
                'description' => $description,
                'updatedAt' => date('Y-m-d H:i:s'),
            ]
        );

        if ($result) {
            echo $result;
        } else {
            echo '0';
        }
    }

    public function vote($data)
    {
        $urlId = $data['serverId'];
        if (!$this->servers->serverExists($urlId)) {
            $this->error([
                'errcode' => '404'
            ]);
            die();
        }
        if (!isLoged($this->session)) {
            header('Location: ' . url('login'));
            die();
        }

        $dateNow = new \DateTime();
        $server = $this->servers->findServer($urlId)[0];

        $result = $this->times->findTime($this->session->user->id, $urlId);
        if ($result) {
            //$userTime = new \DateTime('2020-10-05T04:05:18.134-03:00');
            $userTime = new \DateTime($result->time);
            if ($dateNow >= $userTime) {
                $this->servers->addVote($urlId);
                $newTime = $this->times->updateTime($result->id);
                echo $this->view->render('vote-page', [
                    'title' => 'Server-list |' . $server->name,
                    'canVote' => true,
                    'timer' => $newTime['time'],
                    'serverName' => $server->name,
                    'votes' => ++$server->votes
                ]);
            } else {
                echo $this->view->render('vote-page', [
                    'title' => 'Server-List |' . $server->name,
                    'canVote' => false,
                    'timer' => $userTime->format('c')
                ]);
            }
        } else {
            $now = $dateNow->format('c');
            $content = [
                'user_id' => $this->session->user->id,
                'server_id' => $urlId,
                'time' => $dateNow->add(new \DateInterval('PT12H'))->format('c'),
                'createdAt' => $now,
                'updatedAt' => $now
            ];

            $this->servers->addVote($urlId);
            $this->times->createTime($content);

            echo $this->view->render('vote-page', [
                'title' => 'Server-list |' . $server->name,
                'canVote' => true,
                'timer' => $content['time'],
                'serverName' => $server->name,
                'votes' => ++$server->votes
            ]);
        }
    }

    public function join($data)
    {
        $urlId = $data['serverId'];
        $server = $this->servers->findServer($urlId)[0];
        vd($server->invite);
    }

    public function error($data)
    {
        // :TODO create the errors page
        if ($data['errcode'] === '404') {
            echo 'página não encontrada :(';
        }
    }
}
