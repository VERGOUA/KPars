<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;

class CacheStructureService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create()
    {
        $this->connection->exec('
            CREATE TABLE IF NOT EXISTS `s_films` (
              `id` INT PRIMARY KEY,
              `path` VARCHAR(255),
              `status` INT,
              `html` MEDIUMBLOB,
              `inserted` DATETIME
            );
        ');
    }
}