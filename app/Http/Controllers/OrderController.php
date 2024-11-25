<?php

namespace App\Http\Controllers;

use App\Contracts\OrderServiceInterface;
use App\Http\Requests\Order\OrderTransformRequest;
use Exception;

class OrderController extends Controller
{
    public function __construct(
        private OrderServiceInterface $orderService,
    ) {}

    public function transform(OrderTransformRequest $request)
    {
        $validated = $request->validated();

        try {
            $orderData = $this->orderService->transform($validated);

            return response()->json($orderData);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
