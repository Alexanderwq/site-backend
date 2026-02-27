<?php

namespace App\Model\Account\UseCase\MassageForm\EditMainInfo;

use App\Model\Account\Entity\District\DistrictRepository;
use App\Model\Account\Entity\MassageForm\Description;
use App\Model\Account\Entity\MassageForm\Experience;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageFormRepository;
use App\Model\Account\Entity\MassageForm\Name;
use App\Model\Account\Entity\MassageForm\Phone;
use App\Model\Account\Entity\MetroStation\MetroStationRepository;
use App\Model\Flusher;
use DateTimeImmutable;

readonly class Handler
{
    public function __construct(
        private MassageFormRepository $massageFormRepository,
        private MetroStationRepository $metroStationRepository,
        private DistrictRepository $districtsRepository,
        private Flusher $flusher,
    ) {}

    public function handle(Command $command): void
    {
        $massageForm = $this->massageFormRepository->get(new Id($command->id));
        $massageForm->changeName(new Name($command->name));
        $massageForm->changePhone(new Phone($command->phone));
        $massageForm->changeDescription(new Description($command->description));
        $massageForm->changeDateOfBirth(
            DateTimeImmutable::createFromFormat('Y.m.d', $command->dateOfBirth),
        );
        $massageForm->changeExperience(new Experience($command->experience));
        $massageForm->changeMetroStations($this->metroStationRepository->getByIds($command->metroList));
        $massageForm->changeDistricts($this->districtsRepository->getByIds($command->districtList));

        $this->flusher->flush();
    }
}
