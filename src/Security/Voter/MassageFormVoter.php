<?php

namespace App\Security\Voter;

use App\Model\Account\Entity\MassageForm\MassageForm;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MassageFormVoter extends Voter
{
    public const string ACTIVATE = 'MASSAGE_FORM_ACTIVATE';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::ACTIVATE
            && $subject instanceof MassageForm;
    }

    protected function voteOnAttribute(string $attribute, $massageForm, TokenInterface $token, Vote|null $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        return $massageForm->getUserId()->getValue() === $user->getId();
    }
}
