<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create([
            'user_id' => $data['user_id'],
            'total_price' => $data['total_price'],
            'status' => $data['status'],
            'shipping_address' => $data['shipping_address'],
        ]);
    }

    public function findById(int $id): ?Order
    {
        return Order::with('orderItems.product')->find($id);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update([
            'total_price' => $data['total_price'],
            'status' => $data['status'],
            'shipping_address' => $data['shipping_address'],
        ]);

        return $order;
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }

    public function getUserOrders(int $userId)
    {
        return Order::where('user_id', $userId)->with('orderItems.product')->get();
    }

    public function getOrdersByStatus(string $status)
    {
        return Order::where('status', $status)->with('orderItems.product')->get();
    }

    public function getAllOrders()
    {
        return Order::with('orderItems.product')->get();
    }

    public function getOrderWithItems(int $orderId)
    {
        return Order::with('orderItems.product')->find($orderId);
    }
    
    public function getOrdersWithUser()
    {
        return Order::with('user')->get();
    }

    public function getOrdersWithUserAndItems()
    {
        return Order::with('user')->with('orderItems.product')->get();
    }

    public function getOrdersByUserId(int $userId)
    {
        return Order::where('user_id', $userId)->with('orderItems.product')->get();
    }
}
