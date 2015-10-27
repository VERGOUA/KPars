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

        foreach (array_keys($tables['root']) as $root) {
            $output->writeln("<info>kp:scrap:page $root</info>");
        }

        foreach ($tables['child'] as $root => $tables) {
            foreach (array_keys($tables) as $child) {
                $output->writeln("<info>kp:scrap:sub:page $root $child</info>");
            }
        }

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Time: $duration</info>");
    }
}
