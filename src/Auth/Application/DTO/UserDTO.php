<?php
declare(strict_types=1);

namespace App\Auth\Application\DTO;

class UserDTO
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public string $password,
        public bool $isActive,
        public array $roles
    ) {}
}