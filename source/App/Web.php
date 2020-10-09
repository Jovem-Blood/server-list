<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Models\Servers;

define('OAUTH2_CLIENT_ID', '***REMOVED***');
define('OAUTH2_CLIENT_SECRET', '***REMOVED***');


class Web
{
    private $view;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../themes", "php");
    }

    public function home()
    {
        $servers = new Servers();

        if (isset($_SESSION['user'])) {
            echo $this->view->render("home", [
                'title' => 'Server-List',
                'user' => $_SESSION['user'],
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

    public function login($data)
    {
        $authorizeURL = 'https://discord.com/api/oauth2/authorize';
        $tokenURL = 'https://discord.com/api/oauth2/token';
        $apiURLBase = 'https://discord.com/api/users/@me';

        function apiRequest($url, $post = FALSE, $headers = array())
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $response = curl_exec($ch);


            if ($post)
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

            $headers[] = 'Accept: application/json';

            if (session('access_token'))
                $headers[] = 'Authorization: Bearer ' . session('access_token');

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            return json_decode($response);
        }

        function get($key, $default = NULL)
        {
            return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
        }

        function session($key, $default = NULL)
        {
            return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
        }

        // Start the login process by sending the user to Discord's authorization page
        if (get('action') == 'login') {

            $params = array(
                'client_id' => OAUTH2_CLIENT_ID,
                'redirect_uri' => 'http://localhost/server-list/login',
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
                'redirect_uri' => 'http://localhost/server-list/login',
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
            header('Location: http://localhost/server-list/');
            die();
        } else {
            echo '<h3>Not logged in</h3>';
            echo '<p><a href="?action=login">Log In</a></p>';
        }


        if (get('action') == 'logout') {
            // This must to logout you, but it didn't worked(

            $params = array(
                'access_token' => $logout_token
            );

            // Redirect the user to Discord's revoke page
            header('Location: https://discordapp.com/api/oauth2/token/revoke' . '?' . http_build_query($params));
            die();
        }
    }
}
