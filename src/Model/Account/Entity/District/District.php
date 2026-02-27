<?php

namespace App\Model\Account\Entity\District;

use App\Model\Account\Entity\District\Area\Area;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'account_districts')]
class District
{
    #[ORM\Id, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $descriptor;

    #[ORM\ManyToOne(targetEntity: Area::class)]
    private Area $area;

    public function __construct(int $id, string $name, string $descriptor, Area $area)
    {
        $this->id = $id;
        $this->name = $name;
        $this->descriptor = $descriptor;
        $this->area = $area;
    }
}
