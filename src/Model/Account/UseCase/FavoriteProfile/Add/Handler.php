<?php

namespace App\Model\Account\UseCase\FavoriteProfile\Add;

use App\Model\Account\Entity\FavoriteGroup\FavoriteGroupRepository;
use App\Model\Account\Entity\FavoriteGroup\Id as FavoriteGroupId;
use App\Model\Account\Entity\FavoriteMassageForm\FavoriteMassageForm;
use App\Model\Account\Entity\FavoriteMassageForm\FavoriteMassageFormRepository;
use App\Model\Account\Entity\FavoriteMassageForm\MassageFormId as FavoriteMassageFormId;
use App\Model\Account\Entity\FavoriteMassageForm\UserId;
use App\Model\Account\Entity\MassageForm\Id as MassageFormId;
use App\Model\Account\Entity\MassageForm\MassageFormRepository;
use App\Model\Flusher;
use DateTimeImmutable;
use Exception;

readonly class Handler
{
    public function __construct(
        private FavoriteMassageFormRepository $favoriteProfileRepository,
        private FavoriteGroupRepository       $favoriteGroupRepository,
        private MassageFormRepository         $massageFormRepository,
        private Flusher                       $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        if (!$this->massageFormRepository->exists(new MassageFormId($command->massageForm))) {
            throw new Exception('Massage Form does not exist');
        }

        $group = null;
        if ($command->group) {
            $group = $this->favoriteGroupRepository->get(new FavoriteGroupId($command->group));
        }

        $favoriteProfile = new FavoriteMassageForm(
            new FavoriteMassageFormId($command->massageForm),
            new UserId($command->user),
            new DateTimeImmutable(),
        );

        if ($group) {
            $favoriteProfile->setGroup($group);
        }

        $this->favoriteProfileRepository->add($favoriteProfile);

        $this->flusher->flush();
    }
}
