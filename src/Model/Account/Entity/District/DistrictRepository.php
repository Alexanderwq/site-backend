<?php

namespace App\Model\Account\Entity\District;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DistrictRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $manager)
    {
        $this->repository = $this->manager->getRepository(District::class);
    }

    public function get(int $id): District
    {
        if (!$district = $this->repository->find($id)) {
            throw new \Exception('District not found');
        }

        return $district;
    }

    public function getByIds(array $ids): array
    {
        return $this->repository->findBy(['id' => $ids]);
    }


    public function add(District $district): void
    {
        $this->manager->persist($district);
    }

    public function remove(District $district): void
    {
        $this->manager->remove($district);
    }
}
