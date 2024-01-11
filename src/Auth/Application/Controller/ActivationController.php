<?php
declare(strict_types=1);

namespace App\Auth\Application\Controller;

use App\Auth\Domain\Exception\TokenDoesNotExistException;
use App\Auth\Infrastructure\Repository\TokenRepositoryInterface;
use App\Auth\Infrastructure\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActivationController extends AbstractController
{
    private TokenRepositoryInterface $tokenRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
      TokenRepositoryInterface $tokenRepository,
      UserRepositoryInterface $userRepository
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    #[Route("/activate/{token}", "app_activate")]
    public function activate(Request $request, string $token): Response
    {
        $token = $this->tokenRepository->findByToken($token);

        if($token === null) {
            throw new TokenDoesNotExistException('Token does not exist');
        }

        if($this->isTokenValid($token->getValidUntil()))
        {
            $this->userRepository->activate($token->getUser());
        }
        else {
            //@TODO: regenerate token
            throw new \Exception();
        }

        return $this->redirectToRoute("app_login");
    }

    public function isTokenValid(\DateTime $valid_until): bool
    {
        return !((new \DateTime('now') > $valid_until));
    }
}