<?php

namespace App\Command;

use App\Expense\Service\AutomaticExpenseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExpenseRefreshCommand extends Command
{
    protected static $defaultName = 'expense:refresh';
    /**
     * @var AutomaticExpenseService
     */
    private $automaticExpenseService;

    public function __construct(AutomaticExpenseService $automaticExpenseService)
    {
        parent::__construct();
        $this->automaticExpenseService = $automaticExpenseService;
    }

    protected function configure()
    {
        $this->setDescription('Refresh expenses with recurring payments');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $this->automaticExpenseService->updateExpenses();

        $inputOutput->success('Expenses refreshed');

        return 0;
    }
}
