<?php

namespace App\Model\Account\Entity\MetroStation;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'account_metro_stations')]
class MetroStation
{
    #[ORM\Id, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $descriptor;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $color;

    public function __construct(int $id, string $name, string $descriptor, ?string $color)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->descriptor = $descriptor;
    }
}
