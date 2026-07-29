<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'createdAt' => \DateTimeImmutable::createFromMutable(new \DateTime()),
            'email' => 'admin@admin.fr',
            'password' => '123456',
            'roles' => ['ROLE_ADMIN'],
            'username' => 'admin',
        ]);
        UserFactory::createMany(100);

        $manager->flush();
    }
}
