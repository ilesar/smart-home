<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportProxiesCommand extends Command
{
    protected static $defaultName = 'import:proxies';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import Konzum products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $seedFile = file_get_contents(__DIR__.'/Seed/ProxyServers.sql');
        $this->entityManager->getConnection()->exec($seedFile);

        $inputOutput->success('Proxies imported');

        return 0;
    }
}
