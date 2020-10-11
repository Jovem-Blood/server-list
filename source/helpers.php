<?php
function url (string $url = null): string
{
    if ($url) {
        return URL_BASE . "/$url";
    }
    return URL_BASE;
}


function userIcon (string $userId, $userIcon): string
{

    return 'https://cdn.discordapp.com/avatars/' . $userId . '/'. $userIcon . '.webp';

}

function serverIcon (string $serverId, $serverIcon):string {
    return 'https://cdn.discordapp.com/icons/' . $serverId . '/'. $serverIcon . '.webp';
}

function addBot(string $id=null) {
    if($id) {
        return 'https://discord.com/oauth2/authorize?client_id='. $id .'&permissions=387136&scope=bot';
    }else {
        return 'https://discord.com/oauth2/authorize?client_id=537799173744099328&permissions=387136&scope=bot';
    }
}


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


/*
 * for Debug
 * */

function vd($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
