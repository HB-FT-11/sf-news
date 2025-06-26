<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('zh_TW');

        for ($i = 0; $i < 50; $i++) {
            $article = new Article();

            $article
                ->setTitle($faker->realText(50))
                ->setContent($faker->realTextBetween(300, 800))
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setVisible($faker->boolean(80));

            $manager->persist($article); // L'enregistrement n'est pas encore créé
        }

        $manager->flush(); // L'enregistrement est inséré à ce niveau-là
    }
}