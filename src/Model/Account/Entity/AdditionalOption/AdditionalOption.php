<?php

namespace App\Model\Account\Entity\AdditionalOption;

use App\Model\Account\Entity\OptionGroup\OptionGroup;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'account_additional_options')]
class AdditionalOption
{
    #[ORM\Id, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: OptionGroup::class, inversedBy: 'options')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: false)]
    private OptionGroup $group;

    public function __construct(int $id, string $name, OptionGroup $group)
    {
        $this->id = $id;
        $this->name = $name;
        $this->group = $group;
    }

    public function setGroup(OptionGroup $group): void
    {
        $this->group = $group;
    }
}
