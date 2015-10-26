<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;

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
        $client = new Client([
            'base_uri' => $this->url
        ]);

        $id = $this->getNextId() ?: 1;

        while ($limit--) {
            $path = "/film/$id";

            $data = [
                'id' => $id,
                'path' => $path,
                'inserted' => date('Y-m-d H:i:s')
            ];

            try {
                $response = $client->get($path);
                $data['html'] =  $response->getBody()->getContents();
                $data['status'] =  $response->getStatusCode();
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $data['status'] = $e->getCode();
            }

            $this->add($data);

            ++$id;
        }
    }

    private function add(array $data)
    {
        $this->connection->insert('s_films', $data);
    }

    private function getNextId()
    {
        return $this->connection
            ->executeQuery("
                SELECT MAX(`id`) + 1 FROM `s_films`;
            ")
            ->fetchColumn();
    }
}
