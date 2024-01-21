<?php
declare(strict_types=1);

namespace App\Auth\Domain\Repository;

use App\Auth\Application\Entity\Token;
use App\Auth\Application\Entity\User;
use App\Auth\Infrastructure\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($user);
        $manager->flush();
    }

    public function activate(User $user): void
    {
        $this->getEntityManager()->createQueryBuilder()
            ->update(User::class, 'u')
            ->set('u.is_active', ':newIsActiveStatus')
            ->where('u.email = :userId')
            ->setParameter('newIsActiveStatus', true)
            ->setParameter('userId', $user->getEmail())
            ->getQuery()
            ->execute();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByEmail(string $email): ?User
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}