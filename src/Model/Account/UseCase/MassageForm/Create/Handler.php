<?php

namespace App\Model\Account\UseCase\MassageForm\Create;

use App\Model\Account\Entity\District\DistrictRepository;
use App\Model\Account\Entity\MassageForm\Coords;
use App\Model\Account\Entity\MassageForm\Description;
use App\Model\Account\Entity\MassageForm\Experience;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageForm;
use App\Model\Account\Entity\MassageForm\MassageFormRepository;
use App\Model\Account\Entity\MassageForm\Name;
use App\Model\Account\Entity\MassageForm\Phone;
use App\Model\Account\Entity\MassageForm\UserId;
use App\Model\Account\Entity\MetroStation\MetroStationRepository;
use App\Model\Flusher;
use DateTimeImmutable;

class Handler
{
    public function __construct(
        private readonly Flusher                $flusher,
        private readonly MassageFormRepository  $massageFormRepository,
        private readonly MetroStationRepository $metroStationRepository,
        private readonly DistrictRepository $districtRepository,
    ) {

    }

    public function handle(Command $command): void
    {
        $coords = null;

        if ($command->lat && $command->long) {
            $coords = new Coords($command->lat, $command->long);
        }

        $massageForm = new MassageForm(
            new Id($command->id),
            new UserId($command->userId),
            new Phone($command->phone),
            new Name($command->name),
            new Description($command->description),
            DateTimeImmutable::createFromFormat('Y.m.d', $command->dateOfBirth),
            new Experience($command->experience),
            new DateTimeImmutable(),
            $coords,
        );

        foreach ($command->metroList as $metroId) {
            $metro = $this->metroStationRepository->get($metroId);
            $massageForm->addMetroStation($metro);
        }

        foreach ($command->districtList as $districtId) {
            $district = $this->districtRepository->get($districtId);
            $massageForm->addDistrict($district);
        }

        $this->massageFormRepository->add($massageForm);

        $this->flusher->flush();
    }
}
