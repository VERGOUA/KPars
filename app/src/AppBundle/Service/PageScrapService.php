<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class PageScrapService
{
    private $connection;

    private $url;

    private $tables;

    private $table;

    public function __construct(Connection $connection, array $tables, $url)
    {
        $this->connection = $connection;
        $this->tables = $tables;
        $this->url = $url;
    }

    public function scrap($root, $limit)
    {
        $this->table = $this->tables['root'][$root];

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
            $path = "/$root/$id/";

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
                if ($promise->getState() === PromiseInterface::FULFILLED) {
                    unset($promises[$key]);
                } else {
                    $promise->wait();
                }
            }
        }
    }

    private function add(array $data)
    {
        $this->connection->insert($this->table, $data);
    }

    private function getNextId()
    {
        return $this->connection
            ->executeQuery("
                SELECT MAX(`id`) + 1 FROM `{$this->table}`;
            ")
            ->fetchColumn();
    }
}
