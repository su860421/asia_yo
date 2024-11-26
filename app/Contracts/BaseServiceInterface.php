<?php

namespace App\Contracts;

interface BaseServiceInterface
{
    public function transform(array $orderData);
}
