<?php

namespace App\Command;

use App\Grocery\GroceryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GroceryFetchImagesCommand extends Command
{
    protected static $defaultName = 'grocery:fetch-images';
    /**
     * @var GroceryService
     */
    private $groceryService;

    public function __construct(GroceryService $groceryService)
    {
        parent::__construct();
        $this->groceryService = $groceryService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get images for all products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        set_time_limit(0);
        $inputOutput = new SymfonyStyle($input, $output);

        $this->groceryService->fetchImagesForProducts();

        $inputOutput->success('All images saved');

        return 0;
    }
}
