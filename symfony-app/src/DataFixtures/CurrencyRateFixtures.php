<?php

namespace App\DataFixtures;

use App\Entity\CurrencyRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyRateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadUsdEurRate($manager);

        $manager->flush();
    }

    protected function loadUsdEurRate(ObjectManager $manager) {
        $currencyRate = new CurrencyRate();
        $currencyRate->setSource('EUR')
            ->setTarget('USD')
            ->setRate(1.2);
        $manager->persist($currencyRate);
    }
}
