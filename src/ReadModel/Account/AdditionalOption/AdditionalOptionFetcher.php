<?php

namespace App\ReadModel\Account\AdditionalOption;

use App\Model\Account\Entity\AdditionalOption\AdditionalOption;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AdditionalOptionFetcher
{
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface      $em,
    )
    {
        $this->repository = $em->getRepository(AdditionalOption::class);
    }

    public function get(int $id): AdditionalOption
    {
        return $this->repository->find($id);
    }

    public function list(): array
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from('account_additional_options')
            ->fetchAllAssociative();
    }
}
