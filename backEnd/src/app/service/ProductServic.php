<?php

namespace App\service;

use App\Repository\ProductRepository;


class ProductServic
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function getProductById(int $id)
    {
        return $this->productRepository->findById($id);
    }

    public function updateProduct(int $id, array $data)
    {
        $product = $this->getProductById($id);
        if (!$product) {
            throw new \Exception('Product not found');
        }
        return $this->productRepository->update($product, $data);
    }

    public function deleteProduct(int $id)
    {
        $product = $this->getProductById($id);
        if (!$product) {
            throw new \Exception('Product not found');
        }
        return $this->productRepository->delete($product);
    }

    public function getProductsByCategoryId(int $categoryId)
    {
        return $this->productRepository->getProductsByCategoryId($categoryId);
    }

    public function searchProducts(string $query)
    {
        return $this->productRepository->searchProducts($query);
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAllProducts();
    }

    public function getProductsByIdUser(int $id)
    {
        return $this->productRepository->getProductsByIdUser($id);
    }
}
