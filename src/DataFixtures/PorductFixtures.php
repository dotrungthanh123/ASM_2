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
        //     $product->setImage("https://th.bing.com/th/id/OIP.boU0VLtfyLfKIbp_1YIWJgHaFj?pid=ImgDet&rs=1");
        //     $product->setQuantity($i);
        //     $product->setDescription('sdkjfdsksnjdksf');
        //     $product->setPrice((float) $i);
        //     $manager->persist($product);
        // }
        $manager->flush();
    }
}
