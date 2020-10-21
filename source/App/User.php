<?php


namespace Source\App;


class User
{
    private string $name;
    private string $id;
    private string $avatar;
    private object $servers;

    public function __construct()
    {
        $session = new Session();
        if($session->has('user')) {
            $this->name = $session->user->username;
            $this->id = $session->user->id;
            $this->avatar = $session->user->avatar;
            $this->servers = (object)$session->guilds;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return htmlspecialchars($this->name);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return userIcon($this->id,$this->avatar);
    }

    /**
     * @return object
     */
    public function getServers(): object
    {
        return $this->servers;
    }

}