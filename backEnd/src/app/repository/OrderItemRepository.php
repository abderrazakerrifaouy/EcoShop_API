<?php

namespace App\Repository;

use App\Models\OrderItem;
use App\Jobs\UpdateProductStockJob;

class OrderItemRepository
{
    public function create(array $data)
    {
        // Dispatch job to update product stock
        UpdateProductStockJob::dispatch($data['product_id'], $data['quantity']);

        return OrderItem::create([
            'order_id' => $data['order_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
        ]);
    }

    public function findById(int $id)
    {
        return OrderItem::find($id);
    }

    public function update(OrderItem $orderItem, array $data)
    {
            // Dispatch job to update product stock
        UpdateProductStockJob::dispatch($data['product_id'], $data['quantity']);
        $orderItem->update([
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
        ]);

        return $orderItem;
    }

    public function delete(OrderItem $orderItem): void
    {
        $orderItem->delete();
    }

    public function getOrderItemsByOrderId(int $orderId)
    {
        return OrderItem::where('order_id', $orderId)->with('product')->with('user')->get();
    }
}