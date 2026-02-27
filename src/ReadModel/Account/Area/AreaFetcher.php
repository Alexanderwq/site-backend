<?php

namespace App\ReadModel\Account\Area;

use App\Model\Account\Entity\District\Area\Area;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AreaFetcher
{
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface      $em,
    )
    {
        $this->repository = $em->getRepository(Area::class);
    }

    public function get(int $id): Area
    {
        return $this->repository->find($id);
    }
}
