<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ComplexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('kp:complex');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $tables = $this->getContainer()->getParameter('tables');

        $commands = [];
        foreach (array_keys($tables['root']) as $root) {
            $commands[] = "kp:scrap:page $root";
        }

        foreach ($tables['child'] as $root => $tables) {
            foreach (array_keys($tables) as $child) {
                $commands[] = "kp:scrap:sub:page $root $child";
            }
        }

        foreach ($commands as $command) {
            $output->writeln("<info>$command</info>");
        }

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Time: $duration</info>");
    }
}
