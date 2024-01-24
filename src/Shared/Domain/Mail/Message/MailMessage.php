<?php
declare(strict_types=1);

namespace App\Shared\Domain\Mail\Message;

readonly class MailMessage
{
    public function __construct(
        private string $content,
        private string $title,
        private string $sender,
        private string $recipient,
        private array $context = [],
        private bool $isTemplated = false
    ) { }

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

    public function getContext(): array
    {
        return $this->context;
    }

    public function isTemplated(): bool
    {
        return $this->isTemplated;
    }
}