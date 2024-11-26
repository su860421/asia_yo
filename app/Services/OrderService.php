<?php

namespace App\Services;

use App\Contracts\OrderServiceInterface;
use App\Enums\OrderCurrency;
use Exception;

class OrderService extends BaseService implements OrderServiceInterface
{
    private const USD_TO_TWD_RATE = 31;

    public function __construct() {}

    public function transform(array $orderData)
    {
        $this->validateOrder($orderData);

        if ($orderData['currency'] == OrderCurrency::USD()) {
            $orderData['price'] *= self::USD_TO_TWD_RATE;
            $orderData['currency'] = OrderCurrency::TWD();
        }

        return $orderData;
    }

    private function validateOrder(array $orderData): void
    {
        $this->validateName($orderData['name']);
        $this->validatePrice($orderData['price']);
        $this->validateCurrency($orderData['currency']);
    }

    private function validateName(string $name)
    {
        if (! preg_match('/^[a-zA-Z ]+$/', $name)) {
            throw new Exception('Name contains non-English characters', 400);
        }
        $words = explode(' ', $name);
        foreach ($words as $word) {
            if (! empty($word) && ! ctype_upper(substr($word, 0, 1))) {
                throw new Exception('Name is not capitalized', 400);
            }
        }
    }

    private function validatePrice(int $price)
    {
        if ($price >= 2000) {
            throw new Exception('Price is over 2000', 400);
        }
    }

    private function validateCurrency(string $currency)
    {
        if (! in_array($currency, OrderCurrency::values())) {
            throw new Exception('Currency format is wrong', 400);
        }
    }
}
