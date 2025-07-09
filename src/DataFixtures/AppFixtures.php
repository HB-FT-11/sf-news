<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const NB_CATEGORIES = 8;
    private const NB_ARTICLES = 90;

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('zh_TW');

        // --- CATEGORIES
        $categories = [];

        for ($i = 0; $i < self::NB_CATEGORIES; $i++) {
            $category = new Category();
            $category->setName($faker->word());

            $manager->persist($category);
            $categories[] = $category;
        }

        // --- USERS
        $regularUser = new User();
        $regularUser
            ->setEmail('regular@user.com')
            ->setPassword($this->hasher->hashPassword($regularUser, 'test'));

        $manager->persist($regularUser);

        $adminUser = new User();
        $adminUser
            ->setEmail('admin@user.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($adminUser, 'admin'));

        $manager->persist($adminUser);

        // --- ARTICLES
        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();

            $article
                ->setTitle($faker->realText(12))
                ->setContent($faker->realTextBetween(300, 800))
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setVisible($faker->boolean(80))
                ->setCategory($faker->randomElement($categories));

            $manager->persist($article); // L'enregistrement n'est pas encore créé
        }

        $manager->flush(); // L'enregistrement est inséré à ce niveau-là
    }
}
