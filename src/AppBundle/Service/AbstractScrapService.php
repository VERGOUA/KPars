<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractScrapService
{
    protected $connection;

    protected $url;

    protected $tables;

    protected $table;

    public function __construct(Connection $connection, array $tables, $url)
    {
        $this->connection = $connection;
        $this->tables = $tables;
        $this->url = $url;
    }

    protected function getClient()
    {
        return new Client([
            'base_uri' => $this->url,
            'allow_redirects' => false,
        ]);
    }

    protected function getAccessOptions()
    {
        return [
            'headers' => [
                'Accept-Encoding' => 'gzip, deflate',
            ],
        ];
    }

    protected function process($limit)
    {
        $client = $this->getClient();

        $options = $this->getAccessOptions();

        $promises = [];
        foreach ($this->getNext($limit) as $id) {
            $path = $this->getPath($id);

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
        }

        $this->wait($promises);
    }

    protected function add(array $data)
    {
        $this->connection->insert($this->table, $data);
    }

    protected function wait(array $promises)
    {
        /* @var $promises PromiseInterface[] */
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

    abstract protected function getPath($id);

    abstract protected function getNext($limit);
}