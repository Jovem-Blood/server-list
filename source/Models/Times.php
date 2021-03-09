<?php


namespace Source\Models;

use Source\Models\Connect;
use DateTime, DateInterval;

class Times extends Connect
{

    public function findTime(string $userId, string $serverId)
    {
        $result = $this->from('times')
            ->where('user_id')->is($userId)
            ->andWhere('server_id')->is($serverId)
            ->select()
            ->first();

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
        $updatedTime = $dateNow->add(new DateInterval('PT12H'))->format('c');

        $done =
            $this->update('times')
            ->where('id')->is($timeId)
            ->set([
                'time' => $updatedTime
            ]);

        $result = [
            'done' => $done,
            'time' => $updatedTime
        ];

        return $result;
    }
}
