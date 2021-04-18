<?php

namespace Source\Models;

use Opis\Database\Database;
use Source\Models\Connect;
use Source\Models\Tags;
use CoffeeCode\Paginator\Paginator;

class Servers extends Connect
{

    /**
     * Cria um servidor no banco de dados
     *
     * @param array|object $content
     * @return boolean
     */

    public function createServer($content): bool
    {
        $content = (array)$content;
        $result =
            $this->insert($content)
            ->into('servers');

        return $result;
    }

    /**
     * Atualiza os valores de um servidor do banco de dados
     *
     * @param string $serverId
     * @param array $content o conteúdo que vai ser alterado 
     * @return boolean
     */

    public function updateServer(string $serverId, array $content)
    {
        $result =
            $this->transaction(function (Database $db) use ($content, $serverId) {

                $db->update('servers')
                    ->where('server_id')->is($serverId)
                    ->set($content['update']);

                foreach ($content['addedTags'] as $tagId) {
                    $db->insert(['server_id' => $serverId, 'tag_id' => $tagId])
                        ->into('server_x_tag');
                }

                foreach ($content['removedTags'] as $tagId) {
                    $db->from('server_x_tag')
                        ->where('server_id')->is($serverId)
                        ->andWhere('tag_id')->is($tagId)
                        ->delete();
                }

                return true;
            }, false);

        return $result;
    }


    /**
     * Deleta um servidor do banco de dados
     *
     * @param string $serverId
     * @return boolean
     */

    public function deleteServer(string $serverId): bool
    {

        $result =
            $this->from('servers')
            ->where('server_id')->is($serverId)
            ->delete();

        return $result;
    }

    /**
     * Encontra um servidor no banco e retorna um objeto, caso não exista retorna false
     *
     * @param string $serverId
     * @param array $columns Caso seja necessário buscar colunas expecíficas
     * @param bool $fetchTags Caso seja true, trará as tags do servidor
     * @return object|bool
     */

    public function findServer(string $serverId, array $columns = [], bool $fetchTags = false)
    {
        $result =
            $this->from('servers')
            ->where('server_id')->is($serverId)
            ->select($columns)
            ->first();

        if ($fetchTags == true && $result == true) {
            $result->tags = (new Tags)->getServerTags($serverId);
            return $result;
        }
        return $result;
    }

    public function search(string $query, ?int $page)
    {
        $words = explode(" ", $query);
        $wordsCount = count($words);
        $sql =
            $this->from('servers')
            ->where('name')->like("%" . $words[0] . "%");


        if ($wordsCount > 1) {
            for ($i = 1; $i < $wordsCount; $i++) {
                empty($words[$i]) ?: $sql->orWhere('name')->like("%" . $words[$i] . "%");
            }
        }
        $query = str_replace(" ", "+", $query);
        $pager = new Paginator("?q=$query&page=");
        $pager->pager((clone $sql)->count(), 2, $page);

        $result['servers'] =
            $sql->orderBy('votes', 'desc')
            ->limit($pager->limit())
            ->offset($pager->offset())
            ->select()->all();

        $result['pager'] = $pager;
        return $result;
    }

    /**
     * Adiciona +1 na contagem de votos de um servidor
     *
     * @param string $serverId
     * @return boolean
     */

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

    /**
     * Retorna um array com os servidores que estão publicados
     *
     * @param integer $limit
     * @param array $colum
     * @return array
     */

    public function publishes(int $limit = 6, array $colum = []): array
    {
        $result =
            $this->from('servers')
            ->where('published')->is(1)
            ->orderBy('votes', 'desc')
            ->limit($limit)
            ->select($colum)
            ->all();
        return $result;
    }

    /**
     * Checa se o servidor existe e retorna um bool
     *
     * @param string $serverId
     * @return boolean
     */

    public function serverExists(string $serverId): bool
    {
        if (empty($this->findServer($serverId, ['id']))) {
            return false;
        }
        return true;
    }
}
