<?php


namespace Source\Models;

use Source\Models\Connect;
use DateTime;
use DateInterval;

class Times extends Connect
{

    public function findTime(string $userId, string $serverId)
    {
        $result = $this->from('times')
            ->where('user_id')->is($userId)
            ->andWhere('server_id')->is($serverId)
            ->select()
            ->all();

        if ($result) {
            return $result[0];
        }
        return $result;
    }

    public function findById(string $timeId)
    {
        $result =
            $this->from('times')
            ->where('id')->is($timeId)
            ->select()
            ->all();

        return $result;
    }

    public function createTime(array $content): bool
    {
        $result =
            $this->insert($content)
            ->into('times');

        return $result;
    }

    public function updateTime(string $timeId): array
    {
        $dateNow = new DateTime();
        $updateTime = [
            'time' => $dateNow->add(new DateInterval('PT12H'))->format('c'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];

        $done =
            $this->update('times')
            ->where('id')->is($timeId)
            ->set($updateTime);

        $result = [
            'done' => $done,
            'time' => $updateTime['time']
        ];

        return $result;
    }
}
