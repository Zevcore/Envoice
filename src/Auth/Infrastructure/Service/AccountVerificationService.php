<?php
declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Application\Entity\Token;
use App\Auth\Application\Entity\User;
use App\Auth\Infrastructure\Repository\TokenRepositoryInterface;
use App\Mail\Domain\Message\MailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Factory\UlidFactory;

class AccountVerificationService
{
    private const SENDER_EMAIL = "sender@email.com";

    private string $appName;
    private TokenRepositoryInterface $tokenRepository;
    private MessageBusInterface $bus;

    public function __construct(
        string $appName,
        TokenRepositoryInterface $tokenRepository,
        MessageBusInterface $bus
    ) {
        $this->appName = $appName;
        $this->tokenRepository = $tokenRepository;
        $this->bus = $bus;
    }

    public function sendToken(User $user, string $path): void
    {
        $token = $this->prepareToken($user);
        $activationLink = $this->prepareActivationLink($token, $path);

        $context = [
          "fullname" => sprintf("%s %s", $user->getName(), $user->getSurname()),
          "link" =>  $activationLink
        ];

        $mailMessage = new MailMessage(
            "Mail/activation.html.twig",
            sprintf("%s | %s", $this->appName, 'Verify your account!'),
            self::SENDER_EMAIL,
            $user->getEmail(),
            $context,
            true
        );

        $this->bus->dispatch($mailMessage);
        $this->saveToken($token);
    }

    private function prepareActivationLink(Token $token, string $path): string
    {
        $homePath = str_replace('/register', '', $path);
        return sprintf('%s/activate/%s', $homePath, $token->getToken());
    }

    private function prepareToken(User $user): Token
    {
        $draftToken = hash('sha256', sprintf('%s%s',
            $user->getEmail(),
            uniqid((string) mt_rand(), true)
        ));

        $validUntil = (new \DateTime('now'))->modify('+ 5 minutes');

        $token = new Token();
        $token
            ->setToken($draftToken)
            ->setUser($user)
            ->setValidUntil($validUntil);

        return $token;
    }

    private function saveToken(Token $token): void
    {
        $this->tokenRepository->save($token);
    }
}