<?php

namespace Source\Models;

use Opis\Database\Database;
use Opis\Database\Connection;

class Connect extends Database
{
    public function __construct()
    {
        $connection = new Connection(DB_CONFIG, DB_USER, DB_PASS);
        $connection->options(PDO_OPTIONS);
        parent::__construct($connection);
        return $this;
    }
}
