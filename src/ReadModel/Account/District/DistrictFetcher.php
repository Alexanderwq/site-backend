<?php

namespace App\ReadModel\Account\District;

use App\Model\Account\Entity\District\District;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DistrictFetcher
{
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface      $em,
    )
    {
        $this->repository = $em->getRepository(District::class);
    }

    public function listAll(): array
    {
        return $this->connection->createQueryBuilder()
            ->select("*")
            ->from('account_districts')
            ->fetchAllAssociative();
    }
}
