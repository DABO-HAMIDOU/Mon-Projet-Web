<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {


        $user1  = $this->createUser1();
        $user2  = $this->createUser2();
        $user3  = $this->createUser3();

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->flush();
    }

    private function createUser1(): User
    {
        $superAdmin = new User();
        $passwordHashed = $this->hasher->hashPassword($superAdmin, "Marylove@2021@");

        $superAdmin->setFirstName("jean");
        $superAdmin->setLastName("Dupond");
        $superAdmin->setEmail("jeandupond.com");
        $superAdmin->setRoles(["ROLE_USER"]);
        $superAdmin->setPassword($passwordHashed);
        $superAdmin->setIsVerified(true);
        $superAdmin->setVerifiedAt(new DateTimeImmutable());

        // Pas besoin de setCreatedAt() et setUpdatedAt() car Gedmo gère ces champs automatiquement.

        return $superAdmin;
    }

    private function createUser2(): User
    {
        $superAdmin = new User();
        $passwordHashed = $this->hasher->hashPassword($superAdmin, "Marylove@2021@");

        $superAdmin->setFirstName("JOELLE");
        $superAdmin->setLastName("Dupond");
        $superAdmin->setEmail("joelledupond.com");
        $superAdmin->setRoles(["ROLE_USER"]);
        $superAdmin->setPassword($passwordHashed);
        $superAdmin->setIsVerified(true);
        $superAdmin->setVerifiedAt(new DateTimeImmutable());

        // Pas besoin de setCreatedAt() et setUpdatedAt() car Gedmo gère ces champs automatiquement.

        return $superAdmin;
    }

    private function createUser3(): User
    {
        $superAdmin = new User();
        $passwordHashed = $this->hasher->hashPassword($superAdmin, "Marylove@2021@");

        $superAdmin->setFirstName("Mark");
        $superAdmin->setLastName("Dupond");
        $superAdmin->setEmail("Markdupond.com");
        $superAdmin->setRoles(["ROLE_USER"]);
        $superAdmin->setPassword($passwordHashed);
        $superAdmin->setIsVerified(true);
        $superAdmin->setVerifiedAt(new DateTimeImmutable());

        // Pas besoin de setCreatedAt() et setUpdatedAt() car Gedmo gère ces champs automatiquement.

        return $superAdmin;
    }
}
