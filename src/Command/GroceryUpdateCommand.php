<?php

namespace App\Command;

use App\Grocery\GroceryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GroceryUpdateCommand extends Command
{
    protected static $defaultName = 'grocery:update';
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
            ->setDescription('Updates all grocery warehouses');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        set_time_limit(0);
        $inputOutput = new SymfonyStyle($input, $output);

        $this->groceryService->refreshWarehouses();

        $inputOutput->success('All warehouses updated');

        return 0;
    }
}
