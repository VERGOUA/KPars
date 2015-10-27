<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCacheStructureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('kp:create:cache:structure');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $duration = microtime(true) - $startTime;

        $this->getContainer()->get('kp.cache_structure')->create();

        $output->writeln("<info>Time: $duration</info>");
    }
}
