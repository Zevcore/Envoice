<?php
declare(strict_types=1);

namespace App\Auth\Domain\Message;

use App\Auth\Application\DTO\UserDTO;

readonly class SendActivationTokenMessage
{
    public function __construct(
        private UserDTO $user,
        private string $path
    ) { }

    public function getUser(): UserDTO
    {
        return $this->user;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}