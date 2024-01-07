<?php
declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Application\Entity\User;
use App\Auth\Domain\Enum\Role;
use App\Auth\Domain\Repository\UserRepository;
use App\Auth\Infrastructure\Form\RegisterType;
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
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        // @TODO: Dashboard route
        if($this->getUser()->getRoles() !== null)
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
                ->setRoles([Role::ROLE_USER->value]);

            $password = $passwordHasher->hashPassword($user, $dummyUser['password']);
            $user->setPassword($password);
            $userRepository->save($user);
        }


        return $this->render('Auth/register.html.twig', [
            'form' => $form
        ]);
    }
}