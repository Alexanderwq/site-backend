<?php

namespace App\Model\Account\Entity\MassageForm\Photo;

use App\Model\Account\Entity\MassageForm\MassageForm;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'account_massage_form_photos')]
class Photo
{
    #[ORM\ManyToOne(targetEntity: MassageForm::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(name: 'massage_form_id', referencedColumnName: 'id', nullable: false)]
    private MassageForm $massageForm;

    #[ORM\Id, ORM\Column(type: 'account_massage_form_photo_id')]
    private Id $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isPreview;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isMain;

    public function __construct(MassageForm $massageForm, Id $id, string $name, bool $isPreview, bool $isMain)
    {
        $this->massageForm = $massageForm;
        $this->id = $id;
        $this->name = $name;
        $this->isPreview = $isPreview;
        $this->isMain = $isMain;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMassageForm(): MassageForm
    {
        return $this->massageForm;
    }

    public function isPreview(): bool
    {
        return $this->isPreview;
    }

    public function isMain(): bool
    {
        return $this->isMain;
    }
}
