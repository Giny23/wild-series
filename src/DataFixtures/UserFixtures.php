<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    const USERS = [
        'user1' => [
            'email' => 'admin@gmail.com',
            'role' => 'ROLE_ADMIN',
            'password' => 'admin',
        ],
        'user2' => [
            'email' => 'contributeur@gmail.com',
            'role' => 'ROLE_CONTRIBUTOR',
            'password' => 'contributeur',
        ],
    ];
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $user => $data) {
                $user = new User();
                $user->setEmail($data['email']);
                $user->setPassword($data['password']);
                $user->hashPassword($user->getPassword(), $this->passwordHasher);
                $user->setRoles([$data['role']]);
                $manager->persist($user);
                $this->addReference($user->getEmail(), $user);
            }
        $manager->flush();
    }
}
