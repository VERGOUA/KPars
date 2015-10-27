<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;

class AbstractScrapService
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

    protected function add(array $data)
    {
        $this->connection->insert($this->table, $data);
    }

    /**
     * @param array PromiseInterface[]
     */
    protected function wait(array $promises)
    {
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
}