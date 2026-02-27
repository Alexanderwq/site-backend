<?php

namespace App\ReadModel\Account\MetroStation;

use App\Model\Account\Entity\MetroStation\MetroStation;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class MetroStationFetcher
{
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface      $em,
    )
    {
        $this->repository = $em->getRepository(MetroStation::class);
    }

    /**
     * @return MetroStationView[]
     */
    public function getAll(): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name',
                'descriptor',
                'color',
            )
            ->from('account_metro_stations')
            ->fetchAllAssociative();

        foreach ($result as &$metroStation) {
            $metroStation = new MetroStationView($metroStation['id'], $metroStation['name'], $metroStation['descriptor'], $metroStation['color']);
        }

        return $result;
    }
}
