<?php

namespace App\Security\Voter;

use App\Model\Comment\Entity\Comment\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    public const string DELETE = 'COMMENT_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::DELETE
            && $subject instanceof Comment;
    }

    protected function voteOnAttribute(string $attribute, $comment, TokenInterface $token, Vote|null $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        return $comment->getAuthorId()->getValue() === $user->getId();
    }
}
