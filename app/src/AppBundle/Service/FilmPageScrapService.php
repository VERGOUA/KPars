<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;

class FilmPageScrapService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function scrap($limit)
    {

    }
}
