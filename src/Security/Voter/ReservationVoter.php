<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use App\Entity\Reservation;

class ReservationVoter extends Voter
{
    public const DELETE = 'RESA_DELETE';
    public const VIEW = 'RESA_VIEW';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE, self::VIEW])
            && $subject instanceof \App\Entity\Reservation;
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
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }

    private function canDelete (Reservation $reservation, User $user)
    {
        $today = new \DateTime("now");
        $daysToDelete = $reservation->getStartDate()->diff($today)->format("%a");
        var_dump($daysToDelete);
        if( $user === $reservation->getUser() && $daysToDelete>3) {
            return true;
        }

        return false;

    }

}
