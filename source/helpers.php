<?php

use Source\App\Session;

/**
 * Retorna a url do projeto + caminho
 *
 * @param string $url
 * @return string
 */
function url(string $url = null): string
{
    if ($url) {
        return URL_BASE . "/$url";
    }
    return URL_BASE;
}

/**
 * redireciona para um lugar do site ou fora dele
 *
 * @param string $url
 * @param boolean $here caso precise redirecionar para fora do site $here será false
 * @return void
 */
function redirect(string $url = null, bool $here = true): void
{
    if ($url) {
        if ($here) {
            header('Location: ' . URL_BASE . "/$url");
            die();
        }
        header('Location: ' . "$url");
        die();
    }
    header('Location: ' . URL_BASE);
    die();
}

/**
 * retorna o ícone de um usuário 
 *
 * @param string $userId
 * @param $userIcon
 * @return string
 */
function userIcon(string $userId, $userIcon): string
{

    return 'https://cdn.discordapp.com/avatars/' . $userId . '/' . $userIcon . '.webp';
}

/**
 * retorna o ícone de um servidor
 *
 * @param string $serverId
 * @param string $serverIcon
 * @return string
 */
function serverIcon(string $serverId, string $serverIcon): string
{
    return 'https://cdn.discordapp.com/icons/' . $serverId . '/' . $serverIcon . '.webp';
}

/**
 * retorna o link para adicionar o bot em um servidor
 *
 * @param string $id
 * @return void
 */
function addBot(string $id = null)
{
    if ($id) {
        return 'https://discord.com/oauth2/authorize?client_id=' . $id . '&permissions=387136&scope=bot';
    } else {
        return 'https://discord.com/oauth2/authorize?client_id=537799173744099328&permissions=387136&scope=bot';
    }
}

/**
 * faz um request para uma api e retorna um objeto
 *
 * @param string $url
 * @param boolean $post
 * @param array $headers
 * @return array|object
 */
function apiRequest(string $url, $post = FALSE, array $headers = array())
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch);


    if ($post) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }

    $headers[] = 'Accept: application/json';

    if ($access_token = (new Session)->access_token) {
        $headers[] = 'Authorization: Bearer ' . $access_token;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response);
}

/**
 * retorna um valor da global $_GET
 *
 * @param string $key
 * @param mixed|null $default
 * @return mixed
 */
function get(string $key, $default = NULL)
{
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}


/**
 * Checa se o usuário está logado ou não
 *
 * @param Session $session
 * @return boolean
 */
function isLoged(): bool
{
    if (!(new Session)->has('user')) {
        return false;
    }
    return true;
}

/**
 * substitue/gera um token CSRF
 *
 * @return string
 */

function csrfGenerate(): string
{
    $csrf = (new Session());
    $csrf->csrf();
    return ($csrf->csrf_token ?? "");
}

/**
 * verifica se o csrf do request token é válido
 *
 * @param $_REQUEST $request
 * @return boolean
 */
function csrfVerify($request): bool
{
    $session = new Session();
    if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
        return false;
    }
    return true;
}
