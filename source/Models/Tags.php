<?php


namespace Source\Models;



use CoffeeCode\DataLayer\Connect;

class Tags
{
    public function getTags(string $serverId)
    {
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
    }

}