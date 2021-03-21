<?php


namespace Source\Models;

use Source\Models\Conect;

class Tags extends Connect
{

    public function getServerTags(string $serverId): array
    {
        $result =
            $this->from(['servers' => 's'])
            ->join(['server_x_tag' => 'x'], function ($join) {
                $join->on('s.server_id', 'x.server_id');
            })
            ->join(['tags' => 't'], function ($join) {
                $join->on('x.tag_id', 't.tag_id');
            })
            ->where('s.server_id')->is($serverId)
            ->select(['t.name', 't.tag_id'])
            ->all();

        return $result;
    }

    public function getAllTags(): array
    {
        $result =
            $this->from('tags')
            ->select()
            ->all();
        return $result;
    }
}
