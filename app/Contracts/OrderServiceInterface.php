<?php

namespace App\Contracts;

interface OrderServiceInterface
{
    public function transform(array $orderData);
}
