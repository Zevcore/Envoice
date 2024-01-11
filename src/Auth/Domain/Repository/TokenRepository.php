<?php
declare(strict_types=1);

namespace App\Auth\Domain\Repository;

use App\Auth\Application\Entity\Token;
use App\Auth\Infrastructure\Repository\TokenRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class TokenRepository extends ServiceEntityRepository implements TokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function save(Token $token): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($token);
        $manager->flush();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByToken(string $token): ?Token
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from(Token::class, 't')
            ->where('t.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}