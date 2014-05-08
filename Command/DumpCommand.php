<?php

namespace Astina\Bundle\TranslationBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class DumpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('astina:translation:dump')
            ->setDescription('Dump all translations')
            ->addOption('format', null, InputOption::VALUE_OPTIONAL, 'The format of the dump (php, json)', 'php')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        /** @var $manager \Doctrine\ORM\EntityManager */
        $manager = $doctrine->getManager();

        /** @var $repo \Doctrine\ORM\EntityRepository */
        $repo = $doctrine->getRepository('AstinaTranslationBundle:Translation');

        $translations = $repo->createQueryBuilder('t')
            ->getQuery()
            ->getResult()
        ;

        if ((count($translations)) == 0) {
            $output->writeln('<info>No translations found</info>');
            return;
        }

        $data = array();

	foreach ($translations as $translation) {
            $data[ $translation->getLocale() ][ $translation->getDomain() ][ $translation->getSource() ] = $translation->getTarget();
        }

        switch ($input->getOption('format')) {
            case 'php':
	        $output->writeln(var_export($data, true));
                break;

            case 'json':
	        $output->writeln(json_encode($data));
                break;

            default:
                $output->writeln('<error>Invalid format specified</error>'); 
        }
    }

}
