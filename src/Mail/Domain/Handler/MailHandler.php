<?php
declare(strict_types=1);

namespace App\Mail\Domain\Handler;

use App\Mail\Domain\Message\MailMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
        if($mailMessage->isTemplated()) {
            $email = (new TemplatedEmail())
                ->subject($mailMessage->getTitle())
                ->from($mailMessage->getSender())
                ->to($mailMessage->getRecipient())
                ->htmlTemplate($mailMessage->getContent())
                ->context($mailMessage->getContext());
        }
        else {
            $email = (new Email())
                ->from($mailMessage->getSender())
                ->to($mailMessage->getRecipient())
                ->subject($mailMessage->getTitle())
                ->text($mailMessage->getContent());
        }

        $this->mailer->send($email);
    }
}