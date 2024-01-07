<?php
declare(strict_types=1);

namespace App\Mail\Domain\Message;

readonly class MailMessage
{
    public function __construct(
        private string $content,
        private string $title,
        private string $sender,
        private string $recipient
    ) {

    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }
}