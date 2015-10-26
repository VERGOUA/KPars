<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;

class FilmPageScrapService
{
    private $connection;

    private $url;

    public function __construct(Connection $connection, $url)
    {
        $this->connection = $connection;
        $this->url = $url;
    }

    public function scrap($limit)
    {

    }
}
