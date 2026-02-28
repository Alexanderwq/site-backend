<?php

namespace App\Model\Account\UseCase\FavoriteGroup\Create;

use App\Model\Account\Entity\FavoriteGroup\FavoriteGroup;
use App\Model\Account\Entity\FavoriteGroup\FavoriteGroupRepository;
use App\Model\Account\Entity\FavoriteGroup\Id;
use App\Model\Account\Entity\FavoriteGroup\UserId;
use App\Model\Flusher;
use DomainException;

readonly class Handler
{
    public function __construct(
        private FavoriteGroupRepository $favoriteGroupRepository,
        private Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $userId = new UserId($command->user);
        $existGroup = $this->favoriteGroupRepository->findByUserAndName($userId, $command->name);

        if ($existGroup) {
            throw new DomainException('Group already exists');
        }

        $favoriteGroup = new FavoriteGroup(
            Id::next(),
            $userId,
            $command->name,
        );

        $this->favoriteGroupRepository->add($favoriteGroup);

        $this->flusher->flush();
    }
}
