<?php

namespace App\Command;

use App\Mqtt\Interfaces\TopicSubscriberInterface;
use App\Mqtt\Model\ConfigurationSubscriber;
use App\Mqtt\Model\MeasurementSubscriber;
use App\Mqtt\Model\RegistrationSubscriber;
use App\Mqtt\MqttService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MqttWorkerCommand extends Command
{
    protected static $defaultName = 'core:mqtt';
    /**
     * @var MqttService
     */
    private $mqttService;

    /**
     * @var TopicSubscriberInterface[]
     */
    private $subscribers = [];

    public function __construct(MqttService $mqttService, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->mqttService = $mqttService;

        $this->subscribers = $subscribers = [
            new RegistrationSubscriber($mqttService, $entityManager),
            new ConfigurationSubscriber($mqttService, $entityManager),
            new MeasurementSubscriber($mqttService, $entityManager),
        ];
    }

    protected function configure()
    {
        $this->setDescription('Starts a MQTT client');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        try {
            foreach ($this->subscribers as $subscriber) {
                $this->mqttService->addTopicListener($subscriber);
            }

            $this->mqttService->run();
        } catch (Exception $exception) {
            $inputOutput->error($exception->getMessage());
        }

        $inputOutput->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
