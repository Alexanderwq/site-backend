<?php

namespace App\Model\Account\Entity\FavoriteGroup;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity, Table(name: 'account_favorite_groups')]
class FavoriteGroup
{
    #[ORM\Id, ORM\Column(type: IdType::NAME)]
    private Id $id;

    #[ORM\Column(type: UserIdType::NAME)]
    private UserId $userId;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    public function __construct(Id $id, UserId $userId, string $name)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
