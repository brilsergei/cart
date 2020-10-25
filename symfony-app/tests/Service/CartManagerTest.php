<?php

namespace App\Tests\Service;

use App\Entity\Cart;
use App\Entity\LineItem;
use App\Entity\Product;
use App\Entity\ProductPrice;
use App\Service\CartManager;
use App\Service\CurrencyCalculator;
use PHPUnit\Framework\TestCase;

class CartManagerTest extends TestCase {

    protected $currencyCalculator;

    public function setUp(): void {
        parent::setUp();

        $this->currencyCalculator = $this->createMock(CurrencyCalculator::class);
        $this->currencyCalculator->method('convert')
            ->willReturnArgument(0);
    }

    /**
     * @dataProvider totalPriceCalculationProvider
     */
    public function testCalculateTotalPrice(array $items, float $expectedTotal): void
    {
        $cart = $this->generateLineItems($items);

        $cartManager = new CartManager($this->currencyCalculator, 'USD');
        $total = $cartManager->calculateTotalPrice($cart);
        $this->assertEquals($expectedTotal, $total);
    }

    public function totalPriceCalculationProvider(): array
    {
        return [
            [
                [
                    [10.2, 'USD', 'Test 1', 3],
                ],
                30.6,
            ],
            [
                [],
                0,
            ],
            [
                [
                    [12.5, 'USD', 'Test 1', 3],
                    [20, 'EUR', 'Test 2', 5],
                ],
                137.5,
            ],
        ];
    }

    protected function generateLineItems(array $source): Cart
    {
        $cart = new Cart();
        foreach ($source as $item) {
            $productPrice = new ProductPrice();
            $productPrice->setAmount($item[0])
                ->setCurrency($item[1]);

            $product = new Product();
            $product->setTitle($item[2])
                ->setPrice($productPrice);

            $lineItem = new LineItem();
            $lineItem->setProduct($product)
                ->setQuantity($item[3])
                ->setCart($cart);
            $cart->addLineItem($lineItem);
        }

        return $cart;
    }

}
