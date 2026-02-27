<?php

namespace App\Model\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class UserRepository
{
    private EntityRepository $repo;

    public function __construct(private readonly EntityManagerInterface $manager)
    {
        $this->repo = $this->manager->getRepository(User::class);
    }

    public function findByConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['confirmToken' => $token]);
    }

    public function findByResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['resetToken.token' => $token]);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(Id $id): User
    {
        if (!$user = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('User not found');
        }

        return $user;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getByEmail(Email $email): User
    {
        if (!$user = $this->repo->findOneBy(['email' => $email->getValue()])) {
            throw new EntityNotFoundException('User not found');
        }

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.email = :email')
            ->setParameter('email', $email->getValue())
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->manager->persist($user);
    }
}
