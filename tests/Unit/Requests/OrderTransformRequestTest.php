<?php

declare(strict_types=1);

namespace Tests\Unit\Requests\Prizes;

use App\Enums\OrderCurrency;
use App\Http\Requests\Order\OrderTransformRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class OrderTransformRequestTest extends TestCase
{
    use RefreshDatabase;
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
            'currency' => $this->faker->randomElement(OrderCurrency::values()),
        ];
    }

    private function validate(array $data, array $expectedErrors = [])
    {
        $validator = Validator::make($data, (new OrderTransformRequest)->rules());

        if (! empty($expectedErrors)) {
            $this->assertFalse($validator->passes());
            $this->assertEquals($expectedErrors, $validator->errors()->keys());
        } else {
            $this->assertTrue($validator->passes());
        }
    }

    public function test_authorize_method_returns_true()
    {
        $request = new OrderTransformRequest;
        $this->assertTrue($request->authorize());
    }

    public function test_valid_prize_store_request_passes(): void
    {
        $data = $this->getValidData();
        $this->validate($data);
    }

    public function test_required_fields()
    {
        $this->validate([], ['id', 'name', 'address.city', 'address.district', 'address.street', 'price', 'currency']);
    }

    public function test_id_must_be_a_string()
    {
        $data = $this->getValidData();
        $data['id'] = 123;
        $this->validate($data, ['id']);
    }

    public function test_id_must_be_max_10_characters()
    {
        $data = $this->getValidData();
        $data['id'] = $this->faker->regexify('[A-Za-z0-9]{11}');
        $this->validate($data, ['id']);
    }

    public function test_name_must_be_a_string()
    {
        $data = $this->getValidData();
        $data['name'] = 123;
        $this->validate($data, ['name']);
    }

    public function test_name_must_be_max_255_characters()
    {
        $data = $this->getValidData();
        $data['name'] = $this->faker->regexify('[A-Za-z0-9]{256}');
        $this->validate($data, ['name']);
    }

    public function test_address_city_must_be_a_string()
    {
        $data = $this->getValidData();
        $data['address']['city'] = 123;
        $this->validate($data, ['address.city']);
    }

    public function test_address_city_must_be_max_50_characters()
    {
        $data = $this->getValidData();
        $data['address']['city'] = $this->faker->regexify('[A-Za-z0-9]{51}');
        $this->validate($data, ['address.city']);
    }

    public function test_address_district_must_be_a_string()
    {
        $data = $this->getValidData();
        $data['address']['district'] = 123;
        $this->validate($data, ['address.district']);
    }

    public function test_address_district_must_be_max_50_characters()
    {
        $data = $this->getValidData();
        $data['address']['district'] = $this->faker->regexify('[A-Za-z0-9]{51}');
        $this->validate($data, ['address.district']);
    }

    public function test_address_street_must_be_a_string()
    {
        $data = $this->getValidData();
        $data['address']['street'] = 123;
        $this->validate($data, ['address.street']);
    }

    public function test_address_street_must_be_max_100_characters()
    {
        $data = $this->getValidData();
        $data['address']['street'] = $this->faker->regexify('[A-Za-z0-9]{101}');
        $this->validate($data, ['address.street']);
    }

    public function test_price_must_be_a_numeric()
    {
        $data = $this->getValidData();
        $data['price'] = 'abc';
        $this->validate($data, ['price']);
    }

    public function test_price_must_be_an_integer()
    {
        $data = $this->getValidData();
        $data['price'] = 123.45;
        $this->validate($data, ['price']);
    }

    public function test_price_must_be_min_0()
    {
        $data = $this->getValidData();
        $data['price'] = -1;
        $this->validate($data, ['price']);
    }

    public function test_currency_must_be_a_string()
    {
        $data = $this->getValidData();
        $data['currency'] = 123;
        $this->validate($data, ['currency']);
    }

    public function test_currency_must_be_size_3()
    {
        $data = $this->getValidData();
        $data['currency'] = 'USD1';
        $this->validate($data, ['currency']);
    }
}
