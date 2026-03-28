<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Service\OrderServise;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderServise $orderService)
    {
        $this->orderService = $orderService;
    }

    #[OA\Post(
        path: '/api/orders',
        summary: 'Create a new order',
        tags: ['Orders'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['order', 'ItemsOrder'],
            properties: [
                new OA\Property(
                    property: 'order',
                    type: 'object',
                    required: ['user_id', 'total_price', 'status', 'shipping_address'],
                    properties: [
                        new OA\Property(property: 'user_id', type: 'integer', example: 1),
                        new OA\Property(property: 'total_price', type: 'number', format: 'float', example: 99.99),
                        new OA\Property(property: 'status', type: 'string', example: 'pending'),
                        new OA\Property(property: 'shipping_address', type: 'string', example: '123 Street, Casablanca')
                    ]
                ),
                new OA\Property(
                    property: 'ItemsOrder',
                    type: 'array',
                    items: new OA\Items(
                        required: ['productId', 'quantity', 'price'],
                        properties: [
                            new OA\Property(property: 'productId', type: 'integer', example: 5),
                            new OA\Property(property: 'quantity', type: 'integer', minimum: 1, example: 2),
                            new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 45.50)
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Order created')]
    #[OA\Response(response: 422, description: 'Validation Error')]
    public function store(OrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->all());
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/orders/{id}',
        summary: 'Get order by ID',
        tags: ['Orders'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 200, description: 'Successful response')]
    #[OA\Response(response: 404, description: 'Order not found')]
    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Put(
        path: '/api/orders/{id}',
        summary: 'Update an existing order',
        tags: ['Orders'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'user_id', type: 'integer', example: 1)
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Order updated')]
    public function update(OrderRequest $request, $id)
    {
        try {
            $order = $this->orderService->updateOrder($id, $request->all());
            
            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Delete(
        path: '/api/orders/{id}',
        summary: 'Delete an existing order',
        tags: ['Orders'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 200, description: 'Order deleted successfully')]
    public function destroy($id)
    {
        try {
            $this->orderService->deleteOrder($id);
            return response()->json(['message' => 'Order deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/orders/user/{userId}',
        summary: 'Get orders by user ID',
        tags: ['Orders'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'userId',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 200, description: 'Successful response')]
    public function getOrdersByUserId($userId)
    {
        try {
            $orders = $this->orderService->getOrdersByUserId($userId);
            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/orders/status/{status}',
        summary: 'Get orders by status',
        tags: ['Orders'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'status',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'pending')
    )]
    #[OA\Response(response: 200, description: 'Successful response')]
    public function getOrdersByStatus($status)
    {
        try {
            $orders = $this->orderService->getOrdersByStatus($status);
            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Put(
        path: '/api/orders/{id}/status',
        summary: 'Update order status',
        tags: ['Orders'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'status',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'completed')
    )]
    #[OA\Response(response: 200, description: 'Status updated')]
    public function updateOrderStatus($id, Request $request)
    {
        try {
            $status = $request->query('status');
            $updatedOrder = $this->orderService->updateOrder($id, ['status' => $status]);
            return response()->json($updatedOrder, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}