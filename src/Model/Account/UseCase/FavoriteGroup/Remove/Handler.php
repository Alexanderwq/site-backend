<?php

namespace App\Model\Account\UseCase\FavoriteGroup\Remove;

use App\Model\Account\Entity\FavoriteGroup\FavoriteGroupRepository;
use App\Model\Account\Entity\FavoriteGroup\Id;
use App\Model\Account\Entity\FavoriteGroup\UserId;
use App\Model\Flusher;

readonly class Handler
{
    public function __construct(
        private FavoriteGroupRepository $favoriteGroupRepository,
        private Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $favoriteGroup = $this->favoriteGroupRepository->getByUser(new Id($command->group), new UserId($command->user));

        $this->favoriteGroupRepository->remove($favoriteGroup);

        $this->flusher->flush();
    }
}
