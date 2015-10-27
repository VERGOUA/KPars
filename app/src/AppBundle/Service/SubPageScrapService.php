<?php

namespace AppBundle\Service;

class SubPageScrapService extends AbstractScrapService
{
    public function scrap($root, $child, $limit)
    {
        $this->table = $this->tables['child'][$root][$child];
    }
}