<?php

namespace App\Model\Comment\UseCase\Comment\Remove;

use App\Model\Comment\Entity\Comment\CommentRepository;
use App\Model\Comment\Entity\Comment\Id;
use App\Model\Flusher;

readonly class Handler
{
    public function __construct(private CommentRepository $comments, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        $comment = $this->comments->get(new Id($command->id));

        $this->comments->remove($comment);

        $this->flusher->flush();
    }
}
