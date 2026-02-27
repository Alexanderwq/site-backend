<?php

namespace App\Model\Account\UseCase\MassageForm\EditAdditionalInfo;

use App\Model\Account\Entity\AdditionalOption\AdditionalOptionRepository;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageFormRepository;
use App\Model\Flusher;
use Exception;

readonly class Handler
{
    public function __construct(
        private MassageFormRepository      $massageFormRepository,
        private AdditionalOptionRepository $additionalOptionRepository,
        private Flusher $flusher,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        $massageForm = $this->massageFormRepository->get(new Id($command->massageForm));

        $massageForm->changePrices($command->prices);

        $additionalOptions = [];
        foreach ($command->options as $option) {
            $additionalOptions[] = [
                'option' => $this->additionalOptionRepository->get($option->optionId),
                'price' => $option->price,
                'description' => $option->description,
            ];
        }

        if ($additionalOptions) {
            $massageForm->changeAdditionalOptions($additionalOptions);
        }

        $this->flusher->flush();
    }
}
