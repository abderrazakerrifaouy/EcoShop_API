<?php

namespace App\Service;

use App\repository\OrderRepository;
use App\repository\OrderItemRepository;
class OrderServise
{
    private $orderRepository;
    private $orderItemRepository;

    public function __construct(OrderRepository $orderRepository , OrderItemRepository $orderItemRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function createOrder($data)
    {   
        $order = $data['order'];
        $listItemsOrder = $data['ItemsOrder'];
        // Create the order using the repository
        $createdOrder = $this->orderRepository->create($order);
        // Optionally, you can also handle the creation of order items here using $listItemsOrder
        // For example, you might want to loop through $listItemsOrder and create order items
        if (isset($listItemsOrder) && is_array($listItemsOrder)) {
            foreach ($listItemsOrder as $itemData) {
                $this->orderItemRepository->create([
                    'order_id' => $createdOrder->id,
                    'product_id' => $itemData['productId'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['price'],
                ]);
            }
        }
        return $createdOrder;
    }

    public function getOrderById($id)
    {
        return $this->orderRepository->findById($id);
    }

    public function updateOrder($id, $data)
    {
        $order = $this->getOrderById($id);
        if (!$order) {
            throw new \Exception("Order not found");
        }
        
        $order = $this->orderRepository->update($order , $data);

        return $order;
    }

    public function deleteOrder($id)
    {
        $order = $this->getOrderById($id);
        if (!$order) {
            throw new \Exception("Order not found");
        }
        
        $this->orderRepository->delete($order);
    }

    public function getOrdersByUserId($userId)
    {
        return $this->orderRepository->getUserOrders($userId);
    }

    public function getOrdersByStatus($status)
    {
        return $this->orderRepository->getOrdersByStatus($status);
    }

    
}