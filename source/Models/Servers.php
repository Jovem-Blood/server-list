<?php


namespace Source\Models;
use CoffeeCode\DataLayer\DataLayer;

class Servers extends DataLayer
{

    public function __construct()
    {
        parent::__construct("servers", [], "id", false);
    }

    public function findServer(string $serverId): Servers
    {
        $server = $this->find('server_id = :sId', 'sId='.$serverId)->fetch();
        return $server;
    }

    public function serversIds(): array
    {
        $servers = $this->find()->fetch(true);
        $ids = [];
        foreach ($servers as $server) {
            array_push($ids,$server->server_id);
        }
        return $ids;
    }

    public function publishes(int $limit = null):array
    {
       if($limit) {
            $servers = $this->find('published = :p', ':p=1')->limit($limit)->order("votes DESC")->fetch(true);
        } else {
            $servers = $this->find('published = :p', ':p=1')->order("votes DESC")->fetch(true);
        }
        return $servers;
    }

    public function isPublished(string $serverId):bool
    {
        $servers = $this->publishes();
        $publishesId=[];
        foreach ($servers as $server) {
            array_push($publishesId, $server->server_id);
        }
        if(!in_array($serverId, $publishesId)){
            return false;
        }
        return true;
    }
}
