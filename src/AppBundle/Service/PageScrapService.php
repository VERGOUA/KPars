<?php

namespace AppBundle\Service;

class PageScrapService extends AbstractScrapService
{
    private $root;

    public function scrap($root, $limit)
    {
        $this->table = $this->tables['root'][$root];

        $this->root = $root;

        $this->process($limit);
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

    public function getPath($id)
    {
        return "/{$this->root}/$id/";
    }
}
