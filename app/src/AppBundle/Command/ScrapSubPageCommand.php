<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapSubPageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kp:scrap:sub:page')
            ->addArgument('root', InputArgument::REQUIRED)
            ->addArgument('child', InputArgument::REQUIRED)
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $root = $input->getArgument('root');
        $child = $input->getArgument('child');
        $startTime = microtime(true);
        $limit = (int) $input->getOption('limit');

        $this->getContainer()->get('kp.sub_page_scrap')->scrap($root, $child, $limit);

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Time: $duration</info>");
        $output->writeln("<info>Count: $limit</info>");
    }
}