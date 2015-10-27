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
        dump($input->getArgument('root'), $input->getArgument('child'));
    }
}