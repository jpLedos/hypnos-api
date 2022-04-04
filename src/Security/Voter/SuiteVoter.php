<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use App\Entity\Suite;
use Symfony\Component\Security\Core\Security;


class SuiteVoter extends Voter
{
    public const POST = 'SUITE_POST';
    public const EDIT = 'SUITE_EDIT';
    public const DELETE = 'SUITE_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::POST, self::DELETE])
            && $subject instanceof \App\Entity\Suite;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::POST:
                return $this->canPost($subject, $user);
                // return true or false
                break;
            case self::EDIT:
                return $this->canEdit($subject, $user);
                // return true or false
                break;
            case self::DELETE:
                return $this->canDelete($subject, $user);
                // return true or false
                break;
        }

        return false;
    }

    private function canPost (Suite $suite, User $user)
    {
        if (!$this->security->isGranted('ROLE_MANAGER')) {
            return false;
        }

        if( $user === $suite->getHotel()->getManager()) {
            return true;
        }

        return false;

    }

    private function canEdit (Suite $suite, User $user)
    {

        if( $user === $suite->getHotel()->getManager()) {
            return true;
        }

        return false;

    }

    private function canDelete (Suite $suite, User $user)
    {

        if( $user === $suite->getHotel()->getManager()) {
            return true;
        }

        return false;

    }


}
