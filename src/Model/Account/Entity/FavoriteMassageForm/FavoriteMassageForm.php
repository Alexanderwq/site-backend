<?php

namespace App\Model\Account\Entity\FavoriteMassageForm;

use App\Model\Account\Entity\FavoriteGroup\FavoriteGroup;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'account_favorite_massage_forms', uniqueConstraints: [
    new ORM\UniqueConstraint(columns: ['massage_form_id', 'user_id'])
])]
class FavoriteMassageForm
{
    #[ORM\Id, ORM\Column(type: MassageFormIdType::NAME)]
    private MassageFormId $massageFormId;

    #[ORM\Id, ORM\Column(type: UserIdType::NAME)]
    private UserId $userId;

    #[ORM\ManyToOne(targetEntity: FavoriteGroup::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?FavoriteGroup $group = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    public function __construct(MassageFormId $massageFormId, UserId $userId, DateTimeImmutable $createdAt)
    {
        $this->massageFormId = $massageFormId;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    public function getMassageFormId(): MassageFormId
    {
        return $this->massageFormId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getGroup(): ?FavoriteGroup
    {
        return $this->group;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setGroup(FavoriteGroup $group): void
    {
        $this->group = $group;
    }
}
