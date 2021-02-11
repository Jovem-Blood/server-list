<?php

use Source\App\Session;

/**
 * @param string|null $url
 * @return string
 */
function url (string $url = null): string
{
    if ($url) {
        return URL_BASE . "/$url";
    }
    return URL_BASE;
}


function redirect(string $url = null):void
{
    if($url) {
        header('Location: '. URL_BASE . "/$url");
        die();
    }
    header('Location: '. URL_BASE);
    die();
}

/**
 * @param string $userId
 * @param $userIcon
 * @return string
 */
function userIcon (string $userId, $userIcon): string
{

    return 'https://cdn.discordapp.com/avatars/' . $userId . '/'. $userIcon . '.webp';

}

/**
 * @param string $serverId
 * @param $serverIcon
 * @return string
 */
function serverIcon (string $serverId, $serverIcon):string {
    return 'https://cdn.discordapp.com/icons/' . $serverId . '/'. $serverIcon . '.webp';
}

/**
 * @param string|null $id
 * @return string
 */
function addBot(string $id=null) {
    if($id) {
        return 'https://discord.com/oauth2/authorize?client_id='. $id .'&permissions=387136&scope=bot';
    }else {
        return 'https://discord.com/oauth2/authorize?client_id=537799173744099328&permissions=387136&scope=bot';
    }
}


/**
 * @param $url
 * @param bool $post
 * @param array $headers
 * @return mixed
 */
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

/**
 * @param $key
 * @param null $default
 * @return mixed|null
 */
function get($key, $default = NULL)
{
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

/**
 * @param $key
 * @param null $default
 * @return mixed|null
 */
function session($key, $default = NULL)
{
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}


/**
 * @param object $session
 * @return bool
 */
function isLoged(object $session): bool
{
    if(!$session->has('user')) {
        return false;
    }
    return true;
}

/**
 * @return string
 */
function csrf_input(): string
{
    $csrf = (new Session());
    $csrf->csrf();
    return '<input type="hidden" name="csrf" value="'.($csrf->csrf_token ?? "" ).'"/>';
}

/**
 * @param $request
 * @return bool
 */
function csrf_verify($request): bool
{
    if(empty((new Session())->csrf_token) || empty($request['csrf']) || $request['csrf'] != (new Session())->csrf_token) {
        return true;
    }
    return true;
}

/*
 * for Debug
 * */

/**
 * @param $var
 */
function vd($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
