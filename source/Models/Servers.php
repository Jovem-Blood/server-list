<?php


namespace Source\Models;
use CoffeeCode\DataLayer\DataLayer;

class Servers extends DataLayer
{

    public function __construct()
    {
        parent::__construct("servers", ["icon", "votes", "usersCount", "emoteCount", "name", "description"], "id", false);
    }

    public function serversIds()
    {
        $servers = $this->find()->fetch(true);
        $ids = [];
        foreach ($servers as $server) {
            array_push($ids,$server->server_id);
        }
        return $ids;
    }

}