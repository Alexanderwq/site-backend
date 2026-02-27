<?php

namespace App\Model\Account\Entity\District\Area;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'account_areas')]
class Area
{
    #[ORM\Id, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $abbrName;

    public function __construct(int $id, string $name, string $abbrName)
    {
        $this->id = $id;
        $this->name = $name;
        $this->abbrName = $abbrName;
    }
}
