<?php
declare(strict_types=1);

namespace App\Auth\Domain\Message;

use App\Auth\Application\DTO\UserDTO;

readonly class CreateUserMessage
{
    public function __construct(
        private UserDTO $userDTO,
    )
    { }

    public function getUserDTO(): UserDTO
    {
        return $this->userDTO;
    }

}