<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapPageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kp:scrap:page')
            ->addArgument('root', InputArgument::REQUIRED)
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'limit', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $root = $input->getArgument('root');
        $startTime = microtime(true);
        $limit = (int) $input->getOption('limit');

        $this->getContainer()->get('kp.page_scrap')->scrap($root, $limit);

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Time: $duration</info>");
        $output->writeln("<info>Count: $limit</info>");
    }
}
