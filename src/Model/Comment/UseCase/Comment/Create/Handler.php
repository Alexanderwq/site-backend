<?php

namespace App\Model\Comment\UseCase\Comment\Create;

use App\Model\Comment\Entity\Comment\AuthorId;
use App\Model\Comment\Entity\Comment\Comment;
use App\Model\Comment\Entity\Comment\CommentRepository;
use App\Model\Comment\Entity\Comment\Entity;
use App\Model\Comment\Entity\Comment\Id;
use App\Model\Flusher;
use DateTimeImmutable;

readonly class Handler
{
    public function __construct(private CommentRepository $comments, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        $comment = new Comment(
            new AuthorId($command->author),
            Id::next(),
            new DateTimeImmutable(),
            $command->text,
            new Entity(
                $command->entityType,
                $command->entityId
            )
        );

        $this->comments->add($comment);

        $this->flusher->flush();
    }
}
