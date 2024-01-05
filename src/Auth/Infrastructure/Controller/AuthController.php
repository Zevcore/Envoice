<?php
declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Application\Entity\User;
use App\Auth\Domain\Repository\UserRepository;
use App\Auth\Infrastructure\Form\LoginType;
use App\Auth\Infrastructure\Form\RegisterType;
use App\Core\Domain\Enum\Role;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    public function login(Request $request): Response
    {
        $form = $this->createForm(LoginType::class);

        return $this->render('Auth/index.html.twig', [
           'form' => $form
        ]);
    }

    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
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


        return $this->render('Auth/index.html.twig', [
            'form' => $form
        ]);
    }
}