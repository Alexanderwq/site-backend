<?php

namespace App\Model\Account\Entity\FavoriteGroup;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;

class FavoriteGroupRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $manager)
    {
        $this->repository = $this->manager->getRepository(FavoriteGroup::class);
    }

    public function get(Id $id): FavoriteGroup
    {
        if (!$favoriteGroup = $this->repository->find($id)) {
            throw new Exception('Group not found');
        }

        return $favoriteGroup;
    }

    public function add(FavoriteGroup $favoriteGroup): void
    {
        $this->manager->persist($favoriteGroup);
    }

    public function remove(FavoriteGroup $favoriteGroup): void
    {
        $this->manager->remove($favoriteGroup);
    }
}
