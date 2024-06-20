<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 8;

    private const CATEGORIES = ["Romance", "Thriller", "SF", "Scolaire", "Humour", "Jeunesse", "Histoire", "Cuisine"];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = [];

        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();
            $article
                ->setTitle($faker->words($faker->numberBetween(5, 9), true))
                ->setContent($faker->realTextBetween(400, 600))
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years')))
                ->setVisible($faker->boolean(90))
                ->setCategory($faker->randomElement($categories));

            $manager->persist($article);
        }

        $user = new User();
        $user
            ->setEmail('user@user.com')
            ->setPassword('user');

        $manager->persist($user);

        // $admin = new User();
        // $admin
        //     ->setEmail('admin@admin.com')
        //     ->setPassword('admin')
        //     ->setRoles(['ROLE_ADMIN']);

        // $manager->persist($admin);

        //Envoyer les modifications en BDD:
        $manager->flush();
    }
}
