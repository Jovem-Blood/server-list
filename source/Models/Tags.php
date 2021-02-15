<?php


namespace Source\Models;

use Source\Models\Conect;

class Tags
{
    private object $db;

    public function __construct() {
        $this->db = new Connect();
    }

    public function getTags(string $serverId): array
    {
        $result = 
        $this->db->from(['servers' => 's'])
            ->join(['server_x_tag' => 'x'], function ($join) {
                $join->on('s.server_id', 'x.server_id');
            })
            ->join(['tags' => 't'], function ($join) {
                $join->on('x.tag_id', 't.tag_id');
            })
            ->where('s.server_id')->is($serverId)
            ->select('t.name')
            ->all();
          
        return $result;

        /*
        $conn = Connect::getInstance();
        $err = Connect::getError();
        $sql = "select t.name from servers s join server_x_tag x on s.server_id = x.server_id join tags t on x.tag_id = t.tag_id where s.server_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1,$serverId);
        if($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
        */
    }
}