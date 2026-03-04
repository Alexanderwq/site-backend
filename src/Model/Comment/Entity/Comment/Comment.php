<?php

namespace App\Model\Comment\Entity\Comment;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity]
#[Table(name: 'comment_comments', indexes: [
    new ORM\Index(columns: ['date']),
    new ORM\Index(columns: ['entity_type', 'entity_id']),
])]
class Comment
{
    #[ORM\Id, ORM\Column(type: IdType::NAME)]
    private Id $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: AuthorIdType::NAME)]
    private AuthorId $authorId;

    #[ORM\Embedded(class: Entity::class)]
    private Entity $entity;

    #[ORM\Column(type: Types::STRING)]
    private string $text;

    #[ORM\Column(name: 'update_date', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private DateTimeImmutable $updateDate;

    public function __construct(AuthorId $author, Id $id, DateTimeImmutable $date, string $text, Entity $entity)
    {
        $this->authorId = $author;
        $this->id = $id;
        $this->date = $date;
        $this->text = $text;
        $this->entity = $entity;
    }

    public function edit(DateTimeImmutable $date, string $text): void
    {
        $this->updateDate = $date;
        $this->text = $text;
    }

    public function getAuthorId(): AuthorId
    {
        return $this->authorId;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
