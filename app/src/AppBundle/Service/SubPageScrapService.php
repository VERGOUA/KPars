<?php

namespace AppBundle\Service;

class SubPageScrapService extends AbstractScrapService
{
    public function scrap($root, $child, $limit)
    {
        dump($this->tables['child'][$root][$child]);
    }
}