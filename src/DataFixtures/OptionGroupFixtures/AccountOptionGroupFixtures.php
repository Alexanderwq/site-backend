<?php

namespace App\DataFixtures\OptionGroupFixtures;

use App\Model\Account\Entity\OptionGroup\OptionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountOptionGroupFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $groups = [
            [
                'id' => 1,
                'name' => 'Основные услуги',
            ],
            [
                'id' => 2,
                'name' => 'Дополнительные услуги',
            ],
        ];

        foreach ($groups as $groupData) {
            $group = new OptionGroup(
                (int) $groupData['id'],
                $groupData['name'],
            );

            $manager->persist($group);
        }

        $manager->flush();
    }
}
