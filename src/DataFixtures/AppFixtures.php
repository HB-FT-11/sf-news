<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const NB_CATEGORIES = 8;
    private const NB_ARTICLES = 90;

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
        $users = [];

        $regularUser = new User();
        $regularUser
            ->setEmail('regular@user.com')
            ->setPassword('test');

        $manager->persist($regularUser);
        $users[] = $regularUser;

        $adminUser = new User();
        $adminUser
            ->setEmail('admin@user.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('admin');

        $manager->persist($adminUser);
        $users[] = $adminUser;

        // --- ARTICLES
        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();

            $article
                ->setTitle($faker->realText(12))
                ->setContent($faker->realTextBetween(300, 800))
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setVisible($faker->boolean(80))
                ->setCategory($faker->randomElement($categories))
                ->setAuthor($faker->randomElement($users));

            $manager->persist($article); // L'enregistrement n'est pas encore créé
        }

        // --- API TOKENS
        $token = new ApiToken();
        $token->setToken("7NRtFw5HRBztWKcn93eoDQiG3zZTw8lzP5lrBkRoTnzuc1e1EKvEMhtOlnCsU2");

        $manager->persist($token);

        $manager->flush(); // L'enregistrement est inséré à ce niveau-là
    }
}
