<?php

namespace App\Controller\Api\Account;

use App\ReadModel\Account\AdditionalOption\AdditionalOptionFetcher;
use App\ReadModel\Account\OptionGroup\OptionGroupFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AdditionalOptionsController extends AbstractController
{
    #[Route('/api/search/additional-options', name: 'account.options_list')]
    public function list(AdditionalOptionFetcher $additionalOptionFetcher, OptionGroupFetcher $optionGroupFetcher): JsonResponse
    {
        $options = $additionalOptionFetcher->list();
        $groups = $optionGroupFetcher->list();

        foreach ($groups as &$group) {
            $group['additionalOptions'] = array_filter($options, fn ($option) => $option['group_id'] === $group['id']);
        }

        return $this->json($groups);
    }
}
