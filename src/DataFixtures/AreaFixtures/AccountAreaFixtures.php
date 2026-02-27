<?php

namespace App\DataFixtures\AreaFixtures;

use App\Model\Account\Entity\District\Area\Area;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountAreaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jsonPath = __DIR__ . '/areas.json';
        $areas = json_decode(file_get_contents($jsonPath), true);

        foreach ($areas as $areaData) {
            $area = new Area(
                (int) $areaData['id'],
                $areaData['name'],
                $areaData['abbr_name'],
            );

            $manager->persist($area);
        }

        $manager->flush();
    }
}
