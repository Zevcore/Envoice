<?php
declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Application\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
}