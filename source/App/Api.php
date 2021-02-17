<?php


namespace Source\App;

use Source\Models\Servers;

class Api
{
    protected string $token = DISCORD_TOKEN;
    private $request;
    private Servers $servers;
    public function __construct()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data) && isset($data->token) && $data->token == $this->token) {
            $this->request = $data;
            $this->servers = new Servers();
        } else {
            $this->request = null;
        }
    }

    public function rank()
    {
        if ($this->request) {
            $result = $this->servers->publishes(10, ['name', 'votes']);
            echo json_encode($result);
        }
    }

    public function create()
    {
        if ($this->request) {
            $request = $this->request;
            $result =
                $this->servers->createServer((array)$request->data);

            return json_encode($result);
        }
    }

    public function test()
    {
        if ($this->request) {
            echo $this->request->message->content;
        }
    }
}
