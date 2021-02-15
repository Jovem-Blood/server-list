<?php

namespace Source\Models;

use Source\Models\Conect;

class Servers extends Connect
{

    public function configServer(string $serverId, array $content): bool
    {

        $result =
            $this->update('servers')
            ->where('server_id')->is($serverId)
            ->set($content);

        return $result;
    }

    public function findServer(string $serverId): array
    {
        $result =
            $this->from('servers')
            ->where('server_id')->is($serverId)
            ->select()
            ->all();

        return $result;
    }

    public function findServers(object $guilds): array
    {
        $result = [];
        foreach ($guilds as $guild) {
            $sd =
                $this->from('servers')
                ->where('server_id')->is($guild->id)
                ->select('server_id')
                ->all();

            $sd = $sd[0]->server_id ?? null;
            $sd === null ?: array_push($result, $sd);
        }
        return $result;
    }

    public function addVote(string $serverId): bool
    {
        $result =
            $this->update('servers')
            ->where('server_id')->is($serverId)
            ->increment('votes')
            ->set([
                'updatedAt' => date('Y-m-d H:i:s')
            ]);
        return $result;
    }

    public function publishes(int $limit = null): array
    {
        if ($limit) {
            $result =
                $this->from('servers')
                ->where('published')->is(1)
                ->orderBy('votes', 'desc')
                ->limit($limit)
                ->select()
                ->all();
        } else {
            $result =
                $this->from('servers')
                ->where('published')->is(1)
                ->orderBy('votes', 'desc')
                ->select()
                ->all();
        }
        return $result;
    }

    public function serverExists(string $serverId): bool
    {
        if (empty($this->findServer($serverId))) {
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
