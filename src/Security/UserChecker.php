<?php
namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // Vérification si le compte est bien vérifié
        if (!$user->isVerified()) {
            // L'exception CustomUserMessageAccountStatusException est utilisée pour afficher un message personnalisé à l'utilisateur
            throw new CustomUserMessageAccountStatusException('Vous devez vérifier votre compte par email avant de vous connecter.');
        }
    }
}
