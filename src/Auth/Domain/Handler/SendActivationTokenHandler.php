<?php
declare(strict_types=1);

namespace App\Auth\Domain\Handler;

use App\Auth\Domain\Message\SendActivationTokenMessage;
use App\Auth\Infrastructure\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Service\AccountVerificationService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendActivationTokenHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AccountVerificationService $accountVerificationService
    ) {}

    public function __invoke(SendActivationTokenMessage $message): void
    {
        $user = $this->userRepository->findByEmail($message->getUser()->email);
        $this->accountVerificationService->sendToken($user, $message->getPath());
    }
}