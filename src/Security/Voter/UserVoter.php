<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;


class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';
    public const DELETE = 'USER_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\User;
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
            case self::EDIT:
                return $this->canEdit($subject, $user);
                // return true or false
                break;
            case self::VIEW:
                return $this->canView($subject, $user);
                // return true or false
                break;
            case self::DELETE:
                return $this->canDelete();
                // return true or false
                break;
        }

        return false;
    }


    private function canEdit(User $editingUser, User $user)
    {
   
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if( $user === $editingUser) {
            return true;
        }

        return false;

       // return (!$user->getIsActived()); // on ne peut plus editer un user valider 
    }
    
    private function canView(User $editingUser, User $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if( $user === $editingUser) {
            return true;
        }

        return false;
    }
    
    private function canDelete()
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        return false;

       // return (!$user->getIsActived()); // on ne peut plus editer un user validé 
    }
}
