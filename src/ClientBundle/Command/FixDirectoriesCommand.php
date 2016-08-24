<?php

namespace ClientBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixDirectoriesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('behat:fix-dir')
            ->setDescription('Repairs the directories')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projects = $this
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('ClientBundle:Project')
            ->findAll();

        foreach ($projects as $project) {
            $slug = $this->getContainer()->get('cocur_slugify')->slugify($project->getName());
            if (!is_dir($this->getContainer()->getParameter('client.features_dir') . '/' . $slug)) {
                mkdir($this->getContainer()->getParameter('client.features_dir') . '/' . $slug, 0777, true);
            }
            if (!is_dir($this->getContainer()->getParameter('client.deploy_dir') . '/' . $slug)) {
                mkdir($this->getContainer()->getParameter('client.deploy_dir') . '/' . $slug, 0777, true);
            }
        }
    }
}
