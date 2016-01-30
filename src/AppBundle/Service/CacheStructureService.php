<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;

class CacheStructureService
{
    private $connection;

    private $tables;

    public function __construct(Connection $connection, array $tables)
    {
        $this->connection = $connection;

        $this->tables = $tables;
    }

    public function create()
    {
        foreach ($this->getTables() as $table) {
            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS `$table` (
                  `id` INT PRIMARY KEY,
                  `path` VARCHAR(255),
                  `status` INT,
                  `html` MEDIUMBLOB,
                  `inserted` DATETIME
                );
            ");
        }
    }

    private function getTables()
    {
        foreach ($this->tables['root'] as $table) {
            yield $table;
        }

        foreach ($this->tables['child'] as $name => $tables) {
            foreach ($tables as $table) {
                yield $table;
            }
        }
    }
}