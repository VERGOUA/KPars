<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCacheStructureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('kp:make:cache:structure');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Time: $duration</info>");
    }
}
