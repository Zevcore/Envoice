<?php
declare(strict_types=1);

namespace App\Auth\UI\Controller;

use App\Auth\Application\Entity\Token;
use App\Auth\Application\Entity\User;
use App\Auth\Infrastructure\Repository\TokenRepositoryInterface;
use App\Auth\Infrastructure\Service\AccountVerificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TokenRefreshController extends AbstractController
{
    private const SUPPORT_EMAIL = "support@email.com";
    private TokenRepositoryInterface $tokenRepository;
    private AccountVerificationService $accountVerificationService;

    public function __construct(
        TokenRepositoryInterface $tokenRepository,
        AccountVerificationService $accountVerificationService
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->accountVerificationService = $accountVerificationService;
    }

    #[Route("/token/expired", "app_token_expired")]
    public function index(Request $request): Response
    {
        /* @var Token $token */
        $token = $request->getSession()->get('token');

        if(!isset($token))
        {
            return $this->redirectToRoute("app_register");
        }

        $fullName = $request->getSession()->get('fullname');

        $link = sprintf('%s/refresh/%s',
            str_replace("/expired", '', $request->getUri()),
            $token->getToken()
        );

        return $this->render('Auth/Token/refresh.html.twig', [
            'full_name' => $fullName,
            'link' => $link,
            'support_email' => self::SUPPORT_EMAIL
        ]);
    }

    #[Route("/token/refresh/{token}", "app_token_refresh")]
    public function refresh(Request $request, string $token): Response
    {
        $token = $this->tokenRepository->findByToken($token);
        $this->tokenRepository->remove($token);
        $this->accountVerificationService->sendToken(
            $token->getUser(),
            $request->getSchemeAndHttpHost() . '/'
        );

        return $this->redirectToRoute("app_login");
    }
}