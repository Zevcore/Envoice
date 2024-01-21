<?php
declare(strict_types=1);

namespace App\Auth\UI\Controller;

use App\Auth\Application\DTO\UserDTO;
use App\Auth\Domain\Enum\Role;
use App\Auth\Domain\Message\CreateUserMessage;
use App\Auth\Domain\Message\SendActivationTokenMessage;
use App\Auth\UI\Form\LoginType;
use App\Auth\UI\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class SecurityController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $bus
    ) { }

    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/auth', 'app_auth')]
    public function index(Request $request): Response
    {
        //@TODO: Redirect to dashboard

        $loginForm = $this->createForm(LoginType::class);
        $registerFrom = $this->createForm(RegisterType::class);

        $registerFrom->handleRequest($request);
        $this->handleRegisterForm($registerFrom);

        return $this->render('Auth/auth.html.twig', [
            'loginForm' => $loginForm,
            'registerForm' => $registerFrom
        ]);
    }

    //@TODO: login handler

    private function handleRegisterForm(FormInterface $form): void
    {
        if($form->isSubmitted() && $form->isSubmitted())
        {
            $formData = $form->getData();
            $userDTO = new UserDTO(
                $formData['name'],
                $formData['surname'],
                $formData['email'],
                $formData['password'],
                false,
                [Role::ROLE_USER->value]
            );

            $path = $_SERVER['HTTP_HOST'] . "/";

            $createUserMessage = new CreateUserMessage($userDTO);
            $this->bus->dispatch($createUserMessage);

            $sendActivationTokenMessage = new SendActivationTokenMessage($userDTO, $path);
            $this->bus->dispatch($sendActivationTokenMessage);
        }
    }
}