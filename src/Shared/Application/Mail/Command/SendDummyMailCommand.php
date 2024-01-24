<?php

namespace App\Shared\Application\Mail\Command;

use App\Shared\Domain\Mail\Message\MailMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'mail:send-dummy')]
class SendDummyMailCommand extends Command
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, string $name = null)
    {
        $this->bus = $bus;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Works");
        $this->bus->dispatch(new MailMessage('test', 'title', 'admin@admin.pl', 'test@test.pl'));

        return self::SUCCESS;
    }
}