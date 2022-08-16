<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PorductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product;
        // for ($i = 0; $i < 20; $i++) {
        //     $product->setName("Product $i");
        //     $product->set
        // }
        $manager->flush();
    }
}
