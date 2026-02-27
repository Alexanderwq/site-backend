<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;

readonly class Flusher
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventDispatcher $eventDispatcher,
    ) {

    }

    public function flush(AggregateRoot ...$roots): void
    {
        $this->entityManager->flush();

        foreach ($roots as $root) {
            $this->eventDispatcher->dispatch($root->releaseEvents());
        }
    }
}
