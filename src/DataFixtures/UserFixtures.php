<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasherInterface)   
    {
        $this->hasher = $hasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User;
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, 'admin'));
        $manager->persist($user);

        $user = new User;
        $user->setUsername('customer');
        $user->setRoles(['ROLE_CUSTOMER']);
        $user->setPassword($this->hasher->hashPassword($user, 'customer'));
        $manager->persist($user);

        $manager->flush();
    }
}
