<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Models\Servers;




class Web
{
    private $view;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../themes", "php");
        if(isset($_SESSION['user'])) {
        $this->view->addData(['user' => $_SESSION['user']]);
        }
    }

    public function home()
    {
        $servers = new Servers();

        if (isset($_SESSION['user'])) {
            echo $this->view->render("home", [
                'title' => 'Server-List',
                'guild' => $_SESSION['guilds'],
                'servers' => $servers->find()->fetch(true)
            ]);
        } else {
            echo $this->view->render("home", [
                'title' => 'Server-List',
                'servers' => $servers->find()->fetch(true)
            ]);
        }
    }

    public function profile()
    {
        $servers = new Servers();

        if(isset($_SESSION['user'])){
            echo $this->view->render("profile", [
                'title' => 'Server-List | ' . $_SESSION['user']->username,
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
            $user = apiRequest($apiURLBase);
            $guilds = apiRequest('https://discord.com/api/users/@me/guilds');

            $_SESSION['user'] = $user;
            $_SESSION['guilds'] = $guilds;
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
        } else {

            $servers = new Servers();
            if(in_array($urlId, $servers->serversIds())) {
                $server = $servers->find('server_id = :sId','sId='.$urlId)->fetch();
                echo $this->view->render('servers',[
                    'title' => 'Server-List | ' . $server->name,
                    'server' => $server,
                ]);
            } else {
                echo 'Servidor Não encontrado...';
            }
        }
    }

    public function error($data)
    {
        if($data['errcode'] === '404') {
            echo 'página não encontrada :(';
        }
    }

}
