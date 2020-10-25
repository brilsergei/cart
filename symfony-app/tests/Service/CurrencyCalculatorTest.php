<?php

namespace App\Tests\Service;

use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepository;
use App\Service\CurrencyCalculator;
use PHPUnit\Framework\TestCase;

class CurrencyCalculatorTest extends TestCase {

    public function testConvertWithExistingRate()
    {
        $rate = new CurrencyRate();
        $rate->setSource('EUR')
            ->setTarget('USD')
            ->setRate(1.2);

        $currencyRateRepository = $this->createMock(CurrencyRateRepository::class);
        $currencyRateRepository->method('findOneBy')
            ->willReturn($rate);

        $calculator = new CurrencyCalculator($currencyRateRepository);

        $converted = $calculator->convert(10, 'EUR', 'USD');
        $this->assertEquals(12, $converted);
    }

    public function testConvertWithNoRate()
    {
        $currencyRateRepository = $this->createMock(CurrencyRateRepository::class);
        $currencyRateRepository->method('findOneBy')
            ->willReturn(null);

        $calculator = new CurrencyCalculator($currencyRateRepository);

        $converted = $calculator->convert(10, 'EUR', 'USD');
        $this->assertEquals(10, $converted);
    }


}
