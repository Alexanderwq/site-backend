<?php

namespace App\DataFixtures\AdditionalOptionsFixtures;

use App\DataFixtures\OptionGroupFixtures\AccountOptionGroupFixtures;
use App\Model\Account\Entity\AdditionalOption\AdditionalOption;
use App\Model\Account\Entity\OptionGroup\OptionGroupRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountAdditionalOptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly OptionGroupRepository $optionGroupRepository)
    {

    }

    public function load(ObjectManager $manager): void
    {

        $options = [
            [
                'id' => 1,
                'name' => 'Классический массаж',
                'group_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Расслабляющий массаж',
                'group_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Эротический массаж',
                'group_id' => 2,
            ],
        ];

        foreach ($options as $optionData) {
            $option = new AdditionalOption(
                (int) $optionData['id'],
                $optionData['name'],
                $this->optionGroupRepository->get($optionData['group_id']),
            );

            $manager->persist($option);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [AccountOptionGroupFixtures::class];
    }
}
