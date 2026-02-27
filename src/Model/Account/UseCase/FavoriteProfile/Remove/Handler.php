<?php

namespace App\Model\Account\UseCase\FavoriteProfile\Remove;

use App\Model\Account\Entity\FavoriteMassageForm\FavoriteMassageFormRepository;
use App\Model\Account\Entity\FavoriteMassageForm\MassageFormId;
use App\Model\Account\Entity\FavoriteMassageForm\UserId;
use App\Model\Flusher;

readonly class Handler
{
    public function __construct(
        private FavoriteMassageFormRepository $favoriteProfileRepository,
        private Flusher                       $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $favoriteProfile = $this->favoriteProfileRepository->get(
            new MassageFormId($command->massageForm),
            new UserId($command->user),
        );

        $this->favoriteProfileRepository->remove($favoriteProfile);

        $this->flusher->flush();
    }
}
