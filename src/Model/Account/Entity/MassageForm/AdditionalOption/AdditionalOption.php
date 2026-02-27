<?php

namespace App\Model\Account\Entity\MassageForm\AdditionalOption;

use App\Model\Account\Entity\MassageForm\MassageForm;
use Doctrine\ORM\Mapping as ORM;
use \App\Model\Account\Entity\AdditionalOption\AdditionalOption as AdditionalOptionRoot;

#[ORM\Entity]
#[ORM\Table(
    name: 'account_massage_form_additional_options',
    uniqueConstraints: [
        new ORM\UniqueConstraint(columns: ['massage_form_id', 'additional_option_id']),
    ]
)]
class AdditionalOption
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: MassageForm::class, inversedBy: 'additionalOptions')]
    #[ORM\JoinColumn(name: 'massage_form_id', referencedColumnName: 'id', nullable: false)]
    private MassageForm $massageForm;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: AdditionalOptionRoot::class)]
    #[ORM\JoinColumn(name: 'additional_option_id', referencedColumnName: 'id', nullable: false)]
    private AdditionalOptionRoot $additionalOption;

    #[ORM\Embedded(class: Price::class)]
    private ?Price $price;

    #[ORM\Embedded(class: Description::class)]
    private ?Description $description;

    public function __construct(
        MassageForm $massageForm,
        AdditionalOptionRoot $additionalOption,
        ?Price $price,
        ?Description $description,
    ) {
        $this->massageForm = $massageForm;
        $this->additionalOption = $additionalOption;
        $this->price = $price;
        $this->description = $description;
    }

    public function getMassageForm(): MassageForm
    {
        return $this->massageForm;
    }

    public function getAdditionalOption(): AdditionalOptionRoot
    {
        return $this->additionalOption;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }
}
