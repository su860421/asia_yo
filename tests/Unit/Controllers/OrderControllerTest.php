<?php

namespace Tests\Unit\Controllers;

use App\Contracts\OrderServiceInterface;
use App\Enums\OrderCurrency;
use App\Http\Controllers\OrderController;
use App\Http\Requests\Order\OrderTransformRequest;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use WithFaker;

    private function getValidData(): array
    {
        return [
            'id' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'name' => $this->faker->name,
            'address' => [
                'city' => $this->faker->city,
                'district' => $this->faker->state,
                'street' => $this->faker->streetAddress,
            ],
            'price' => $this->faker->randomFloat(0, 0, 1000),
            'currency' => OrderCurrency::TWD(),
        ];
    }

    public function test_transform_success()
    {
        $data = $this->getValidData();
        $orderServiceMock = Mockery::mock(OrderServiceInterface::class);
        $orderServiceMock->shouldReceive('transform')
            ->once()
            ->andReturn($data);

        $request = new OrderTransformRequest;
        $request->merge($data);
        $request->setValidator(Validator::make($request->all(), $request->rules()));

        $controller = new OrderController($orderServiceMock);

        $response = $controller->transform($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data, $response->getData(true));
    }

    public function test_transform_failure()
    {
        $orderServiceMock = Mockery::mock(OrderServiceInterface::class);
        $orderServiceMock->shouldReceive('transform')
            ->once()
            ->andThrow(new Exception('Price is over 2000', 400));

        $failData = $this->getValidData();
        $failData['price'] = 2001;

        $request = new OrderTransformRequest;
        $request->merge($this->getValidData());
        $request->setValidator(Validator::make($request->all(), $request->rules()));

        $controller = new OrderController($orderServiceMock);

        $response = $controller->transform($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['message' => 'Price is over 2000'], $response->getData(true));
    }
}
