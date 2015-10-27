<?php

namespace AppBundle\Service;

use Psr\Http\Message\ResponseInterface;

class PageScrapService extends AbstractScrapService
{
    public function scrap($root, $limit)
    {
        $this->table = $this->tables['root'][$root];

        $client = $this->getClient();

        $options = $this->getAccessOptions();

        $promises = [];
        foreach ($this->getNext($limit) as $id) {
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
        }

        $this->wait($promises);
    }

    protected function getNext($limit)
    {
        $last = $this->connection
            ->executeQuery("
                SELECT MAX(`id`) FROM `{$this->table}`;
            ")
            ->fetchColumn();

        while ($limit--) {
            yield ++$last;
        }
    }
}
