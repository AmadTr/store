<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture{

    public function load(ObjectManager $manager): void{

        $faker= \Faker\Factory::create('fr-FR');
        // Generation de categories
        // ************************
        for ($c=0; $c < rand(1,3); $c++) { 

            $cat = new Category();
            $cat->setName($faker->word()); //nom de la cat = 1 mot
            $manager->persist($cat);
    
            // Génération des produits
            // **********************
            for ($a=0; $a < rand(1,3); $a++) {   

                $prod = new Product();
                $prod->setName($faker->word());
                $prod->setQuantity($faker->randomNumber(3,false));
                $prod->setPrice($faker->randomFloat(2,1,200));
                $prod->setCategory($cat);
                $manager->persist($prod);
            }
        $manager->flush();
        }
    }
}
