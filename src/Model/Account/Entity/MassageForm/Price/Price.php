<?php

namespace App\Model\Account\Entity\MassageForm\Price;

use App\Model\Account\Entity\MassageForm\MassageForm;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Guid\Guid;

#[ORM\Entity, ORM\Table(name: 'account_massage_form_prices', uniqueConstraints: [
    new ORM\UniqueConstraint(
        name: 'uniq_massage_form_location',
        columns: ['massage_form_id', 'location', 'duration'],
    )
])]
class Price
{
    #[ORM\Column(type: 'guid'), ORM\Id]
    private string $id;

    #[ORM\ManyToOne(targetEntity: MassageForm::class, inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private MassageForm $massageForm;

    #[ORM\Embedded(class: Location::class)]
    private Location $location;

    #[ORM\Embedded(class: Duration::class)]
    private Duration $duration;

    #[ORM\Embedded(class: Amount::class)]
    private Amount $amount;

    #[ORM\Embedded(class: DayTime::class)]
    private $dayTime;

    public function __construct(
        MassageForm $massageForm,
        Location $location,
        Duration $duration,
        Amount $amount,
        DayTime $dayTime,
    ) {
        $this->id = Guid::uuid4()->toString();
        $this->massageForm = $massageForm;
        $this->location = $location;
        $this->duration = $duration;
        $this->amount = $amount;
        $this->dayTime = $dayTime;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getDuration(): Duration
    {
        return $this->duration;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getMassageForm(): MassageForm
    {
        return $this->massageForm;
    }

    public function dayTime(): DayTime
    {
        return $this->dayTime;
    }
}
