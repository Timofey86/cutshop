<?php

namespace Support\Casts;

use Domain\Product\Models\Product;
use InvalidArgumentException;
use Support\ValueObjects\Price;
use Tests\TestCase;

class PriceCastTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function it_get_success(): void
    {
        $cast = new PriceCast();
        $product = new Product();
        $value = 10000;
        $attributes = ['price' => $value];
        $result = $cast->get($product, 'price', $value, $attributes);
        $this->assertInstanceOf(Price::class, $result);
        $this->assertSame($value, $result->raw());

    }

    /**
     * @test
     * @return void
     */
    public function it_set_success(): void
    {
        $cast = new PriceCast();
        $product = new Product();
        $value = Price::make(100);
        $attributes = ['price' => $value];

        $result = $cast->set($product, 'price', $value, $attributes);

        $this->assertIsInt($result);
        $this->assertSame(100, $result);

    }

    /**
     * @test
     * @return void
     */
    public function it_get_with_invalid_value(): void
    {
        $cast = new PriceCast();
        $product = new Product();
        $value = -100;
        $attributes = ['price' => $value];

        $this->expectException(InvalidArgumentException::class);
        $cast->get($product, 'price', $value, $attributes);
    }

    /**
     * @test
     * @return void
     */
    public function it_set_with_invalid_value(): void
    {
        $cast = new PriceCast();
        $product = new Product();
        $value = -2000;
        $attributes = ['price' => $value];

        $this->expectException(InvalidArgumentException::class);
        $cast->set($product, 'price', $value, $attributes);

    }

}
