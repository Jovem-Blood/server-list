<?php


namespace Source\Models;
use CoffeeCode\DataLayer\DataLayer;

class Times extends DataLayer
{
    public function __construct()
    {
        parent::__construct("times", ['user_id', 'server_id', 'time'], "id", false);
    }

    public function getTime(string $userId,string $serverId)
    {
        $result = $this->find('user_id = :uId AND server_id = :sId', 'uId='.$userId.'&sId='.$serverId)->fetch();
        if(!isset($result)) {
            return false;
        }
        return $result;
    }
}