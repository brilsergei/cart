<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Product;

class CartManager {

    /**
     * @var \App\Service\CurrencyCalculator
     */
    protected $currencyCalculator;

    /**
     * @var string
     */
    protected $defaultCurrency;

    public function __construct(CurrencyCalculator $currencyCalculator, string $defaultCurrency)
    {
        $this->currencyCalculator = $currencyCalculator;
        $this->defaultCurrency = $defaultCurrency;
    }

    public function calculateTotalPrice(Cart $cart)
    {
        $totalPrice = 0;
        foreach ($cart->getLineItems() as $lineItem) {
            $product = $lineItem->getProduct();
            if ($product instanceof Product) {
                $productPrice = $product->getPrice();
                $totalPrice += $lineItem->getQuantity() * $this->currencyCalculator->convert($productPrice->getAmount(), $productPrice->getCurrency(), $this->defaultCurrency);
            }
        }

        return round($totalPrice, 2);
    }

}