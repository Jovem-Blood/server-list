<?php


namespace Source\App;

use CoffeeCode\Router\Router;
use Source\Models\Servers;

class Api
{
    protected string $token = DISCORD_TOKEN;
    public function test($data)
    {
        /*
        :TODO criar as funções da api
        $data = json_decode(file_get_contents("php://input"), true) ?? null;
        if ($data['token'] === $this->token) {
            $data = (object)$data;
            echo json_encode($data);
        } else {
            echo 'erro';
        }*/
    }
}
