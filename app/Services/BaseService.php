<?php

namespace App\Services;

use App\Contracts\BaseServiceInterface;

abstract class BaseService implements BaseServiceInterface
{
    public function __construct() {}

    public function transform(array $data)
    {
        return $data;
    }
}
