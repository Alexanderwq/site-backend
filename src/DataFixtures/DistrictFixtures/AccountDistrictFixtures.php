<?php

namespace App\DataFixtures\DistrictFixtures;

use App\DataFixtures\AreaFixtures\AccountAreaFixtures;
use App\Model\Account\Entity\District\District;
use App\ReadModel\Account\Area\AreaFetcher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountDistrictFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly AreaFetcher $areaFetcher)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $jsonPath = __DIR__ . '/districts.json';
        $districts = json_decode(file_get_contents($jsonPath), true);

        foreach ($districts as $districtData) {
            $area = $this->areaFetcher->get($districtData['area_id']);

            $district = new District(
                (int) $districtData['id'],
                $districtData['name'],
                $districtData['descriptor'],
                $area,
            );

            $manager->persist($district);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AccountAreaFixtures::class,
        ];
    }
}
