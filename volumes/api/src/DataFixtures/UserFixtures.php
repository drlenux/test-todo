<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const TEST_USER_EMAIL = 'test@local.host';
    public const TEST_USER_PASSWORD = 'test';
    public const TEST_USER_REFERENCE = 'test-user';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail(self::TEST_USER_EMAIL);

        $password = $this->passwordHasher->hashPassword($user, self::TEST_USER_PASSWORD);
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::TEST_USER_REFERENCE, $user);
    }
}
