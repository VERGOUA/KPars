<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

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
            'base_uri' => $this->url,
            'allow_redirects' => false,
        ]);

        $id = $this->getNextId() ?: 1;

        $options = [
            'headers' => [
                'Accept-Encoding'	=> 'gzip, deflate',
            ],
        ];

        /* @var $promises PromiseInterface[] */
        $promises = [];
        while ($limit--) {
            $path = "/film/$id/";

            $promises[] = $client
                ->getAsync($path, $options)
                ->then(function (ResponseInterface $response) use ($id, $path) {
                    $this->add([
                        'id' => $id,
                        'path' => $path,
                        'inserted' => date('Y-m-d H:i:s'),
                        'html' => $response->getBody()->getContents(),
                        'status' => $response->getStatusCode(),
                    ]);
                });
            ++$id;
        }

        while ($promises) {
            foreach ($promises as $key => $promise) {
                $promise->wait();

                if ($promise->getState() === PromiseInterface::FULFILLED) {
                    unset($promises[$key]);
                }
            }
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
