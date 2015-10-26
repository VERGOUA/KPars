<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FilmPageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('kp:scrap:filp:page');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);
        $limit = 3;

        $this->getContainer()->get('kp.film_page_scrap')->scrap($limit);

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Time: $duration</info>");
        $output->writeln("<info>Count: $limit</info>");
    }
}
