<?php

namespace Tests\Unit\Services;

use App\Enums\OrderCurrency;
use App\Services\OrderService;
use Exception;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    use WithFaker;

    private $orderService;

    private const USD_TO_TWD_RATE = 31.0;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->orderService = new OrderService;
    }

    private function getValidData(): array
    {
        return [
            'id' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'name' => $this->faker->regexify('[A-Z]{10}'),
            'address' => [
                'city' => $this->faker->city(),
                'district' => $this->faker->state(),
                'street' => $this->faker->streetAddress(),
            ],
            'price' => $this->faker->randomFloat(0, 0, 1000),
            'currency' => $this->faker->randomElement(OrderCurrency::values()),
        ];
    }

    public function test_transform_usd_to_twd()
    {
        $orderData = $this->getValidData();
        $orderData['currency'] = OrderCurrency::USD();

        $transformedOrder = $this->orderService->transform($orderData);

        $this->assertEquals($orderData['price'] * self::USD_TO_TWD_RATE, $transformedOrder['price']);
        $this->assertEquals(OrderCurrency::TWD(), $transformedOrder['currency']);
    }

    public function test_transform_invalid_name()
    {
        $orderData = $this->getValidData();
        $orderData['name'] = 'TestOrder123!';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Name contains non-English characters');

        $this->orderService->transform($orderData);
    }

    public function test_transform_name_not_capitalized()
    {
        $orderData = $this->getValidData();
        $orderData['name'] = 'Test order';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Name is not capitalized');

        $this->orderService->transform($orderData);
    }

    public function test_transform_price_over_limit()
    {
        $orderData = $this->getValidData();
        $orderData['price'] = 2001;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Price is over 2000');

        $this->orderService->transform($orderData);
    }

    public function test_transform_invalid_currency()
    {
        $orderData = $this->getValidData();
        $orderData['currency'] = 'INVALID';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Currency format is wrong');

        $this->orderService->transform($orderData);
    }
}
