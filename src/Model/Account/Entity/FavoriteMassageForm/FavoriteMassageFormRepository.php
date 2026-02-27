<?php

namespace App\Model\Account\Entity\FavoriteMassageForm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;

readonly class FavoriteMassageFormRepository
{
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $manager)
    {
        $this->repository = $this->manager->getRepository(FavoriteMassageForm::class);
    }

    public function get(MassageFormId $id, UserId $userId): FavoriteMassageForm
    {
        if (!$favoriteProfile = $this->repository->findOneBy(['massageFormId' => $id, 'userId' => $userId])) {
            throw new Exception('Profile not found');
        }

        return $favoriteProfile;
    }

    public function add(FavoriteMassageForm $favoriteProfile): void
    {
        $this->manager->persist($favoriteProfile);
    }

    public function remove(FavoriteMassageForm $favoriteProfile): void
    {
        $this->manager->remove($favoriteProfile);
    }
}
