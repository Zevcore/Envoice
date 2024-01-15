<?php
declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Application\Entity\Token;

interface TokenRepositoryInterface
{
    public function save(Token $token): void;
    public function findByToken(string $token): ?Token;
    public function remove(?Token $token): void;
}