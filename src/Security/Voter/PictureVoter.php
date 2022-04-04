<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use App\Entity\Picture;
use Symfony\Component\Security\Core\Security;

class PictureVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'PICT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Picture;
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
            case self::DELETE:
                return $this->canDelete($subject, $user);
                // return true or false
                break;
            case self::EDIT:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }

    private function canDelete (Picture $picture, User $user)
    {

        if (!$this->security->isGranted('ROLE_MANAGER')) {
            return false;
        }

        if( count($picture->getSuites()) === 0 && 
            count($picture->getGalleries()) === 0) {
            
                return true;
        }

        return false;

    }

}
