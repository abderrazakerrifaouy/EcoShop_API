<?php

namespace App\Repository;

use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class ProductRepository
{
    public function create(array $data): Product
    {
        return Product::create([
            'category_id' => $data['category_id'],
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
            'stock' => $data['stock'] ?? 0,

        ]);
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],
            'image' => $data['image'] ?? null,
            'stock' => $data['stock'] ?? $product->stock,
        ]);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function getProductsByCategoryId(int $categoryId)
    {
        return Product::where('category_id', $categoryId)->get();
    }

    public function searchProducts(string $query)
    {
        return Product::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->get();
    }

    public function getAllProducts()
    {
        return Product::all();
    }

    public function getProductsByIdUser(int $id)
    {
        return Product::where('user_id', $id)->get();
    }

    public function AddStock(int $id, int $quantity): Product
    {
        $product = $this->findById($id);
        if (!$product) {
            throw new \Exception("Product not found");
        }
        $product->stock += $quantity;
        $product->save();

        return $product;
    }

    public function RemoveStock(int $id, int $quantity): Product
    {
        $product = $this->findById($id);
        if (!$product) {
            throw new \Exception("Product not found");
        }
        if ($product->stock < $quantity) {
            throw new \Exception("Not enough stock available");
        }
        $product->stock -= $quantity;
        $product->save();

        return $product;
    }
}