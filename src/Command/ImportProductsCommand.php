<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportProductsCommand extends Command
{
    protected static $defaultName = 'import:products';
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

        $seedFile = file_get_contents(__DIR__.'/Seed/KonzumProducts.sql');

        try {
            $this->entityManager->getConnection()->exec($seedFile);
        } catch (Exception $e) {
            dd('test');
        }

        $inputOutput->success('Products imported');

        return 0;
    }
}
