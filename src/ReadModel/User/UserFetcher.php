<?php

namespace App\ReadModel\User;

use App\Model\User\Entity\User\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserFetcher
{
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface $em,
    ) {
        $this->repository = $em->getRepository(User::class);
    }

    public function findForAuth(string $email): ?AuthView
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'name_value as name',
                'role',
                'status',
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->fetchAssociative();

        return $result ? new AuthView(
            $result['id'],
            $result['email'],
            $result['password_hash'],
            $result['name'],
            $result['role'],
            $result['status'],
        ) : null;
    }

    public function get(string $id): User
    {
        if (!$user = $this->repository->find($id)) {
            throw new \Exception('User not found');
        }

        return $user;
    }

    public function getUserInfoForAccount(string $email): ?InfoView
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'email',
                'name_value as name',
                'status',
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->fetchAssociative();

        return $result ? new InfoView(
            $result['email'],
            $result['name'],
            $result['status'],
        ) : null;
    }
}
