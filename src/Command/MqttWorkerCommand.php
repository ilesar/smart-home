<?php

namespace App\Command;

use App\Service\MqttWorkerService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MqttWorkerCommand extends Command
{
    protected static $defaultName = 'core:mqtt';

    /**
     * @var MqttWorkerService
     */
    private $mqttWorkerService;

    public function __construct(MqttWorkerService $mqttWorkerService)
    {
        parent::__construct();
        $this->mqttWorkerService = $mqttWorkerService;
    }

    protected function configure()
    {
        $this->setDescription('Starts a MQTT client');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        try {
            $this->mqttWorkerService->run();
        } catch (Exception $exception) {
            $inputOutput->error($exception->getMessage());

            return 1;
        }

        $inputOutput->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
