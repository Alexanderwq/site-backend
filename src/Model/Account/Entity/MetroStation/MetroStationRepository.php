<?php

namespace App\Model\Account\Entity\MetroStation;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class MetroStationRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $manager)
    {
        $this->repository = $this->manager->getRepository(MetroStation::class);
    }

    public function get(int $id): MetroStation
    {
        if (!$metroStation = $this->repository->find($id)) {
            throw new \Exception('Metro station not found');
        }

        return $metroStation;
    }

    public function getByIds(array $ids): array
    {
        return $this->repository->findBy(['id' => $ids]);
    }

    public function add(MetroStation $massageForm): void
    {
        $this->manager->persist($massageForm);
    }

    public function remove(MetroStation $massageForm): void
    {
        $this->manager->remove($massageForm);
    }
}
