<?php

namespace App\Service;

use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepository;

class CurrencyCalculator {

    /**
     * @var \App\Repository\CurrencyRateRepository
     */
    protected $currencyRateRepository;

    /**
     * @var \App\Entity\CurrencyRate[]
     */
    protected $rates;

    public function __construct(CurrencyRateRepository $currencyRateRepository)
    {
        $this->currencyRateRepository = $currencyRateRepository;
    }

    public function convert(float $amount, string $source, string $target)
    {
        return round($amount * $this->getRate($source, $target), 2);
    }

    protected function getRate(string $source, string $target)
    {
        if (!isset($this->rates[$source][$target])) {
            $rate = $this->currencyRateRepository->findOneBy(['source' => $source, 'target' => $target]);
            $this->rates[$source][$target] = $rate instanceof CurrencyRate ? $rate->getRate() : 1;
        }

        return $this->rates[$source][$target];
    }

}