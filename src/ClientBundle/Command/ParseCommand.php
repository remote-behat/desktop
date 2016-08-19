<?php

namespace ClientBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('behat:parse')
            ->setDescription('Parses a Behat file')
            ->setHelp("This command parses a Behat file")
            ->addArgument('file', InputArgument::REQUIRED, 'The file to parse.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = $input->getArgument('file');

        $feature = $this->getContainer()->get('client.parser.feature')->parse($fileName);

        var_dump($feature);
    }
}
