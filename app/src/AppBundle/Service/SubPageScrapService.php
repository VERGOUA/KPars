<?php

namespace AppBundle\Service;

class SubPageScrapService extends AbstractScrapService
{
    private $root;
    private $child;

    public function scrap($root, $child, $limit)
    {
        $this->table = $this->tables['child'][$root][$child];

        $this->root = $root;
        $this->child = $child;

        $this->process($limit);
    }

    protected function getPath($id)
    {
        return "/{$this->root}/$id/{$this->child}/";
    }

    protected function getNext($limit)
    {
        $child = $this->table;

        $parent = $this->tables['root'][$this->root];

        $result = $this->connection->fetchAll("
            SELECT `id` FROM `$parent`
            WHERE `id` NOT IN (
              SELECT `id` FROM `$child`
            )
            AND `status` = 200
            LIMIT $limit;
        ");

        return array_column($result, 'id');
    }
}
