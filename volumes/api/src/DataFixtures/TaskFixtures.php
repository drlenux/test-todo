<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::TEST_USER_REFERENCE);
        $now = new \DateTimeImmutable();

        $rootTask = (new Task())
            ->setUser($user)
            ->setTitle('A')
            ->setCreatedAt($now);

        $manager->persist($rootTask);

        for ($i = 0; $i < 10; $i++) {
            $task = (new Task())
                ->setTitle("A{$i}")
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUser($user)
                ->setParentTask($rootTask);
            $manager->persist($task);
        }
        $otherTask = (new Task())
            ->setUser($user)
            ->setTitle('B')
            ->setCreatedAt($now);

        $manager->persist($otherTask);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}