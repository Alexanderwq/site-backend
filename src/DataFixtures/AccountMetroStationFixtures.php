<?php

namespace App\DataFixtures;

use App\Model\Account\Entity\MetroStation\MetroStation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountMetroStationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jsonPath = __DIR__ . '/metro_stations.json';
        $stations = json_decode(file_get_contents($jsonPath), true);

        foreach ($stations as $stationData) {
            $station = new MetroStation(
                (int) $stationData['id'],
                $stationData['name'],
                $stationData['descriptor'],
                $stationData['color'] ?? null,
            );

            $manager->persist($station);
        }

        $manager->flush();
    }
}
