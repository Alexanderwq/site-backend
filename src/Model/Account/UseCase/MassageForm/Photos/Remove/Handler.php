<?php

namespace App\Model\Account\UseCase\MassageForm\Photos\Remove;

use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageFormRepository;
use App\Model\Account\Entity\MassageForm\Photo\Id as PhotoId;
use App\Model\Flusher;

readonly class Handler
{
    public function __construct(
        private MassageFormRepository $massageFormRepository,
        private Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $massageForm = $this->massageFormRepository->get(new Id($command->massageForm));

        foreach ($command->photos as $photo) {
            $massageForm->removePhoto(new PhotoId($photo));
        }

        $this->flusher->flush();
    }
}
