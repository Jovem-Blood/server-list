<?php

namespace Source\App;


use League\Plates\Engine;
use Source\Models\{Servers, Tags, Times};

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

    public function login()
    {
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
            redirect('https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params), false);
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
            $this->session->set('access_token', $token->access_token);

            redirect($_SERVER['PHP_SELF']);
        }

        if ($this->session->access_token) {
            $user = apiRequest($apiURLBase);
            $guilds = apiRequest('https://discord.com/api/users/@me/guilds');

            $this->session->set('user', $user);
            $this->session->set('guilds', $guilds);

            redirect();
            die();
        } else {
            redirect('login?action=login');
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
        if (!isLoged()) {
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
        if ($_REQUEST && !csrfVerify($_REQUEST)) {
            echo "csrf inválido, use um formulário";
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

        $result = $this->servers->updateServer(
            $data['serverId'],
            [
                'invite' => $invite,
                'description' => $description,
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
        if (!isLoged()) {
            header('Location: ' . url('login'));
            die();
        }

        $dateNow = new \DateTime();
        $server = $this->servers->findServer($urlId)[0];

        $result = $this->times->findTime($this->session->user->id, $urlId);
        $pageContent = [
            'title' => 'Server-list |' . $server->name,
            'canVote' => true,
            'serverName' => $server->name,
            'votes' => ++$server->votes
        ];
        if ($result) {
            //$userTime = new \DateTime('2020-10-05T04:05:18.134-03:00');
            $userTime = new \DateTime($result->time);
            if ($dateNow >= $userTime) {
                $this->servers->addVote($urlId);
                $pageContent['timer'] = $this->times->updateTime($result->id);
                echo $this->view->render('vote-page', $pageContent);
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

            $pageContent['timer'] = $content['time'];
            echo $this->view->render('vote-page', $pageContent);
        }
    }

    public function join($data)
    {
        $urlId = $data['serverId'];
        $server = $this->servers->findServer($urlId)[0];
        var_dump($server->invite);
    }

    public function error($data)
    {
        // :TODO create the errors page
        if ($data['errcode'] === '404') {
            echo 'página não encontrada :(';
        }
    }
}
