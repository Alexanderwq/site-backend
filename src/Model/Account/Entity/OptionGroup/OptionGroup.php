<?php

namespace App\Model\Account\Entity\OptionGroup;

use App\Model\Account\Entity\AdditionalOption\AdditionalOption;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'account_group_options')]
class OptionGroup
{
    #[ORM\Id, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(targetEntity: AdditionalOption::class, mappedBy: 'group', cascade: ['persist'], orphanRemoval: true)]
    private Collection $options;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->options = new ArrayCollection();
    }

    public function addOption(AdditionalOption $option): void
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setGroup($this);
        }
    }
}
