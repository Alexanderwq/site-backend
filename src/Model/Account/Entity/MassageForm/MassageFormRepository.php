<?php

namespace App\Model\Account\Entity\MassageForm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class MassageFormRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $manager)
    {
        $this->repository = $this->manager->getRepository(MassageForm::class);
    }

    public function get(Id $id): MassageForm
    {
        if (!$massageForm = $this->repository->find($id)) {
            throw new \Exception('Massage form not found');
        }

        return $massageForm;
    }

    public function exists(Id $id): bool
    {
        return $this->repository->find($id) !== null;
    }

    public function add(MassageForm $massageForm): void
    {
        $this->manager->persist($massageForm);
    }

    public function remove(MassageForm $massageForm): void
    {
        $this->manager->remove($massageForm);
    }
}
