<?php

namespace App\EventSubscriber;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandExecutionTimeSubscriber implements EventSubscriberInterface
{
    private float $startTime;

    public static function getSubscribedEvents(): array
    {
        return [
            'console.command' => 'onConsoleCommand',
            'console.terminate' => 'onConsoleTerminate'
        ];
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->startTime = microtime(true);
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $executionTime = microtime(true) - $this->startTime;

        $command = $event->getCommand();
        $output = $event->getOutput();

        $output->writeln(sprintf(
            "\n<info>Command \"%s\" executed in %.2f seconds</info>",
            $command->getName(),
            $executionTime
        ));
    }
}
