<?php

namespace App\Model\Account\Entity\AdditionalOption;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AdditionalOptionRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $manager)
    {
        $this->repository = $this->manager->getRepository(AdditionalOption::class);
    }

    public function get(int $id): AdditionalOption
    {
        if (!$option = $this->repository->find($id)) {
            throw new \Exception('Additional option not found');
        }

        return $option;
    }

    public function add(AdditionalOption $option): void
    {
        $this->manager->persist($option);
    }

    public function remove(AdditionalOption $option): void
    {
        $this->manager->remove($option);
    }
}
