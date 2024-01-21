<?php
declare(strict_types=1);

namespace App\Auth\Domain\Handler;

use App\Auth\Application\Entity\User;
use App\Auth\Domain\Message\CreateUserMessage;
use App\Auth\Infrastructure\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
readonly class CreateUserHandler
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(CreateUserMessage $createUserMessage): void
    {
        $user = new User();
        $userDTO = $createUserMessage->getUserDTO();

        $user
            ->setName($userDTO->name)
            ->setSurname($userDTO->surname)
            ->setEmail($userDTO->email)
            ->setIsActive($userDTO->isActive)
            ->setRoles($userDTO->roles);

        $password = $this->hasher->hashPassword($user, $userDTO->password);
        $user->setPassword($password);

        $this->userRepository->save($user);
    }
}