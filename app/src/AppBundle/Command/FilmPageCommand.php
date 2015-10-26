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
        $this->getContainer()->get('kp.film_page_scrap')->scrap(3);
        $output->writeln('<info>OK</info>');
    }
}
