<?php

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Infrastructure\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route("/", "app_login")]
    public function index(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        /*
        @TODO: redirect to dashboard if logged
        if($this->getUser())
        {
            $this->redirectToRoute('');
        }
        */

        $form = $this->createForm(LoginType::class);

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Auth/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error,
            'form' => $form
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Logging out...');
    }
}