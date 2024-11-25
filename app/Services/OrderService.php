<?php

namespace App\Services;

use App\Contracts\OrderServiceInterface;
use App\Enums\OrderCurrency;
use Exception;

class OrderService implements OrderServiceInterface
{
    public function __construct() {}

    public function transform(array $orderData)
    {
        if (! preg_match('/^[a-zA-Z0-9 ]+$/', $orderData['name'])) {
            throw new Exception('Name contains non-English characters', 400);
        } elseif (! ctype_upper(substr($orderData['name'], 0, 1))) {
            throw new Exception('Name is not capitalized', 400);
        }

        if ($orderData['price'] >= 2000) {
            throw new Exception('Price is over 2000', 400);
        }

        if (! in_array($orderData['currency'], OrderCurrency::values())) {
            throw new Exception('Currency format is wrong', 400);
        } elseif ($orderData['currency'] === OrderCurrency::USD()) {
            $orderData['price'] *= 31;
            $orderData['currency'] = OrderCurrency::TWD();
        }

        return $orderData;
    }
}
