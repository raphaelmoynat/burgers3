<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 2; $i++) {

            $burger = new Burger();
            $burger->setName($faker->name);
            $burger->setPrice($faker->randomDigit());

            $manager->persist($burger);

            for ($j = 0; $j < 3; $j++) {

                $comment = new Comment();
                $comment->setContent($faker->sentence);
                $comment->setBurger($burger);


                $manager->persist($comment);

            }

        }

        $manager->flush();
    }
}


