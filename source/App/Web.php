<?php

namespace Source\App;


use League\Plates\Engine;
use Source\Models\{Servers, Tags, Times, User};

class Web
{
    private Engine $view;
    private Servers $servers;
    private User $user;
    private Session $session;
    private Times $times;
    private Tags $tags;

    public function __construct()
    {
        $this->session = new Session();
        $this->user = new User();
        $this->servers = new Servers();
        $this->times = new Times();
        $this->tags = new Tags();

        $this->view = Engine::create(__DIR__ . "/../../themes", "php");
        if ($this->session->has('user')) {
            $this->view->addData(['user' => $this->user]);
        }
    }

    public function home()
    {
        echo $this->view->render("home", [
            'title' => 'Server-List',
            'servers' => $this->servers->publishes(),
        ]);
    }

    public function search($data)
    {
        $q = $data['q'];
        echo $this->view->render("search", [
            'title' => 'Server-List',
            'servers' => $this->servers->search($q)
        ]);
    }

    public function profile()
    {

        if (!isLoged()) {
            header('Location: ' . url('login'));
            die();
        }

        $userGuilds = $this->user->getGuild();
        $guilds = [];

        foreach ($userGuilds as $guild) {
            if ($guild->permissions == '2147483647') {
                $guild->exists = false;
                if ($this->servers->serverExists($guild->id)) {
                    $guild->exists = true;
                }
                array_push($guilds, $guild);
            }
        }

        echo $this->view->render("profile", [
            'title' => 'Server-List | ' . $this->user->getName(),
            'guilds' => $guilds,
            'profile' => true
        ]);
    }

    public function login()
    {

        if (get('action') == 'login') {

            $authorizeUrl = 'https://discordapp.com/api/oauth2/authorize?';

            $params = [
                'client_id' => OAUTH2_CLIENT_ID,
                'redirect_uri' => URL_BASE . '/login',
                'response_type' => 'code',
                'scope' => 'identify guilds'
            ];

            redirect($authorizeUrl . http_build_query($params), false);
        }


        if (get('code')) {

            $tokenURL = 'https://discord.com/api/oauth2/token';

            $token = apiRequest($tokenURL, [
                "grant_type" => "authorization_code",
                'client_id' => OAUTH2_CLIENT_ID,
                'client_secret' => OAUTH2_CLIENT_SECRET,
                'redirect_uri' => URL_BASE . '/login',
                'code' => get('code')
            ]);

            if ($token->access_token) {
                $this->session->set('access_token', $token->access_token);

                $apiURLBase = 'https://discord.com/api/users/@me';

                $user = apiRequest($apiURLBase);
                $guilds = apiRequest('https://discord.com/api/users/@me/guilds');

                $this->session->set('user', $user);
                $this->session->set('guilds', $guilds);

                redirect();
            }
        }

        redirect('login?action=login');
    }

    public function logout()
    {
        $this->session->destroy();
        redirect();
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

        $server = $this->servers->findServer($urlId, [], true);
        echo $this->view->render('servers', [
            'title' => 'Server-List | ' . $server->name,
            'server' => $server
        ]);
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
                'server' => $this->servers->findServer($urlId, ['server_id', 'name', 'description', 'invite'], true),
                'allTags' => $this->tags->getAllTags()
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

        if ($data['server'] === $data['static']) {
            die();
        }

        $server = json_decode($data['server'], 1);
        $static = json_decode($data['static'], 1);

        $invite = $server['invite'];
        $description = $server['description'];
        $errorMessage = [];
        $serverId = $data['serverId'];

        if (in_array('', $server)) {
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

        $serverTags = $server['tags'];
        $staticTags = $static['tags'];
        $column = 'tag_id';

        $addedTags =
            array_diff(
                array_column($serverTags, $column),
                array_column($staticTags, $column)
            );

        $removedTags =
            array_diff(
                array_column($staticTags, $column),
                array_column($serverTags, $column)
            );

        $result =
            $this->servers->updateServer(
                $serverId,
                [
                    'update' => [
                        'invite' => $invite,
                        'description' => $description,
                        'published' => 1
                    ],
                    'removedTags' => $removedTags,
                    'addedTags' => $addedTags
                ]
            );


        if ($result) {
            $response = [
                'edit' => 'succsess',
                'message' => 'Edição efeituada com sucesso'
            ];
        } else {
            $response = [
                'edit' => 'error',
                'message' => [
                    'Erro inesperado, tente novamente mais tarde'
                ]
            ];
        }

        echo json_encode($response);
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
            redirect('login');
        }

        $dateNow = new \DateTime();
        $server = $this->servers->findServer($urlId, ['name', 'votes']);

        $result = $this->times->findTime($this->user->getId(), $urlId);
        $pageContent = [
            'title' => 'Server-list | ' . $server->name,
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
                    'title' => 'Server-List | ' . $server->name,
                    'canVote' => false,
                    'timer' => $userTime->format('c')
                ]);
            }
        } else {
            $now = $dateNow->format('c');
            $content = [
                'user_id' => $this->user->getId(),
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
        $server = $this->servers->findServer($urlId);
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
