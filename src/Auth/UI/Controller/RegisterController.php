<?php
declare(strict_types=1);

namespace App\Auth\UI\Controller;

use App\Auth\Application\Entity\User;
use App\Auth\Domain\Enum\Role;
use App\Auth\Infrastructure\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Service\AccountVerificationService;
use App\Auth\UI\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegisterController extends AbstractController
{
    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/register', 'app_register')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepositoryInterface $userRepository,
        AccountVerificationService $accountVerificationService
    ): Response {
        // @TODO: Dashboard route
        if($this->getUser() !== null)
        {
            return $this->redirectToRoute("app_login");
        }

        $form = $this->createForm(RegisterType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dummyUser = $form->getData();
            $user = (new User())
                ->setName($dummyUser['name'])
                ->setSurname($dummyUser['surname'])
                ->setEmail($dummyUser['email'])
                ->setIsActive(false)
                ->setRoles([Role::ROLE_USER->value]);

            $password = $passwordHasher->hashPassword($user, $dummyUser['password']);
            $user->setPassword($password);
            $userRepository->save($user);

            $accountVerificationService->sendToken($user, $request->getUri());

            // @TODO: Redirect to dashbaord
            return $this->redirectToRoute("app_login");
        }


        return $this->render('Auth/register.html.twig', [
            'form' => $form
        ]);
    }
}