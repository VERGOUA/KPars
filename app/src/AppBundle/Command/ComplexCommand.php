<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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

        $commands = [array_shift($commands)];
        /* @var $processes Process[] */
        $processes = [];
        foreach ($commands as $command) {
            $processes[$command] = $process = new Process("app/console $command");
            $output->writeln("<info>$command start</info>");
            $process->start();
        }

        while (true) {
            foreach ($processes as $command => $process) {
                if ($process->isRunning()) {
                    $output->writeln("Running $command: <info>{$process->getPid()}</info>");
                } else {
                    $output->writeln($process->getOutput());
                    $processes[$command] = $process->restart();
                }
                sleep(1);
            }
        }

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Time: $duration</info>");
    }
}
