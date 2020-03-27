<?php

namespace App\Command;

use App\Proxy\ProxyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProxyRefreshCommand extends Command
{
    protected static $defaultName = 'proxy:refresh';
    /**
     * @var ProxyService
     */
    private $proxyService;

    public function __construct(ProxyService $proxyService)
    {
        parent::__construct();
        $this->proxyService = $proxyService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Refresh DB with new proxy servers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $this->proxyService->refreshProxyDatabase();

        $inputOutput->success('Proxy database refreshed');

        return 0;
    }
}
