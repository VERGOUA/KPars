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
        $output->writeln('<info>OK</info>');
    }
}