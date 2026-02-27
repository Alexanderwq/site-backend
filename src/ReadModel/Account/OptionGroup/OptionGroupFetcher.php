<?php

namespace App\ReadModel\Account\OptionGroup;

use App\Model\Account\Entity\OptionGroup\OptionGroup;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class OptionGroupFetcher
{
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface      $em,
    )
    {
        $this->repository = $em->getRepository(OptionGroup::class);
    }

    public function get(int $id): OptionGroup
    {
        return $this->repository->find($id);
    }

    public function list(): array
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from('account_group_options')
            ->fetchAllAssociative();
    }
}
