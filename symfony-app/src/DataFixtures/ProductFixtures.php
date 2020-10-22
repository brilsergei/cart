<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductPrice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private const NUMBER_OF_PRODUCTS = 5;

    private static $productNames = [
        'Fallout',
        'Don\'t starve',
        'Baldur\'s gate',
        'Icewind Date',
        'Bloodborne',
    ];

    private static $currencies = ['USD', 'EUR'];

    public function load(ObjectManager $manager)
    {
        $totalCountOfNames = count(static::$productNames);
        $totalCountOfCurrencies = count(static::$currencies);
        for ($i = 0; $i < static::NUMBER_OF_PRODUCTS; $i++) {
            $price = new ProductPrice();
            $price->setAmount(rand(100, 10000) / 100)
                ->setCurrency(static::$currencies[$i % $totalCountOfCurrencies]);

            $product = new Product();
            // TODO Implement generator of unique names
            $product->setTitle(static::$productNames[$i % $totalCountOfNames])
                ->setPrice($price);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
