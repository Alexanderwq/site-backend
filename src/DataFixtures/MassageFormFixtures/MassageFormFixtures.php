<?php

namespace App\DataFixtures\MassageFormFixtures;

use App\DataFixtures\AccountMetroStationFixtures;
use App\DataFixtures\DistrictFixtures\AccountDistrictFixtures;
use App\Model\Account\Entity\District\District;
use App\Model\Account\Entity\MassageForm\Coords;
use App\Model\Account\Entity\MetroStation\MetroStation;
use App\Model\Account\Entity\MassageForm\Id as MassageFormId;
use App\Model\Account\Entity\MassageForm\MassageForm;
use App\Model\Account\Entity\MassageForm\Name;
use App\Model\Account\Entity\MassageForm\Phone;
use App\Model\Account\Entity\MassageForm\Description;
use App\Model\Account\Entity\MassageForm\Experience;
use App\Model\Account\Entity\MassageForm\UserId;
use App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\PriceDto;
use App\Model\Account\UseCase\MassageForm\Photos\Add\PhotoDto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class MassageFormFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $metroStations = $manager->getRepository(MetroStation::class)->findBy([], null, 10);
        $districts = $manager->getRepository(District::class)->findBy([], null, 10);

        for ($i = 1; $i <= 50; $i++) {
            $massageForm = new MassageForm(
                MassageFormId::next(),
                new UserId(UserId::next()),
                new Phone('7' . rand(9000000000, 9999999999)),
                new Name('Массажистка ' . $i),
                new Description('Описание массажиста ' . $i),
                new DateTimeImmutable('1990-01-0' . ($i % 9 + 1)),
                new Experience(random_int(1, 5)),
                new DateTimeImmutable()->modify("-$i day"),
                new Coords(88.123, 12.22),
                $metroStations,
                $districts
            );

            $massageForm->changePrices([
                new PriceDto('home', 1, 2000, 'day'),
                new PriceDto('visitor', 2, 3000, 'night'),
            ]);

            $photos = [
                new PhotoDto("photo_main_$i.jpg", true, false),
                new PhotoDto("photo_preview_$i.jpg", false, true),
                new PhotoDto("photo1_$i.jpg", false, false),
                new PhotoDto("photo2_$i.jpg", false, false),
                new PhotoDto("photo3_$i.jpg", false, false),
            ];
            $massageForm->changePhotos($photos);

            $manager->persist($massageForm);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AccountMetroStationFixtures::class,
            AccountDistrictFixtures::class,
        ];
    }
}
