<?php
declare(strict_types=1);

namespace App\Mail\Application\Handler;

use App\Mail\Domain\Message\MailMessage;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class MailHandler
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(MailMessage $mailMessage): void
    {
        $email = (new Email())
            ->from($mailMessage->getSender())
            ->to($mailMessage->getRecipient())
            ->subject($mailMessage->getTitle())
            ->text($mailMessage->getContent());

        $this->mailer->send($email);
    }
}