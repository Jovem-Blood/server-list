<?php

namespace Source\Models;

use Source\Models\Conect;

class Servers extends Connect
{

    public function createServer($content): bool
    {
        $content = (array)$content;
        $result =
            $this->insert($content)
            ->into('servers');

        return $result;
    }

    public function updateServer(string $serverId, array $content): bool
    {

        $result =
            $this->update('servers')
            ->where('server_id')->is($serverId)
            ->set($content);

        return $result;
    }

    public function deleteServer(string $serverId): bool
    {

        $result =
            $this->from('servers')
            ->where('server_id')->is($serverId)
            ->delete();

        return $result;
    }

    public function findServer(string $serverId, array $columns = []): array
    {
        $result =
            $this->from('servers')
            ->where('server_id')->is($serverId)
            ->select($columns)
            ->all();

        return $result;
    }

    public function getServersIds(object $guilds): array
    {
        $results = [];
        foreach ($guilds as $guild) {
            $result =
                $this->from('servers')
                ->where('server_id')->is($guild->id)
                ->select('server_id')
                ->all();

            if ($result) {
                array_push($results, $result[0]->server_id);
            }
        }
        return $results;
    }

    public function addVote(string $serverId): bool
    {
        $result =
            $this->update('servers')
            ->where('server_id')->is($serverId)
            ->set(
                array(
                    'votes' => function ($expr) {
                        $expr->column('votes')->{'+'}->value(1);
                    },
                    'updatedAt' => date('Y-m-d H:i:s')
                )
            );
        return $result;
    }

    public function publishes(int $limit = null, array $colum = []): array
    {
        if ($limit) {
            $result =
                $this->from('servers')
                ->where('published')->is(1)
                ->orderBy('votes', 'desc')
                ->limit($limit)
                ->select($colum)
                ->all();
        } else {
            $result =
                $this->from('servers')
                ->where('published')->is(1)
                ->orderBy('votes', 'desc')
                ->select($colum)
                ->all();
        }
        return $result;
    }

    public function serverExists(string $serverId): bool
    {
        if (empty($this->findServer($serverId, ['id']))) {
            return false;
        }
        return true;
    }

    public function isPublished(string $serverId): bool
    {
        $result =
            $this->from('servers')
            ->where('server_id')->is($serverId)
            ->select('published')
            ->all();

        return $result;
    }
}
