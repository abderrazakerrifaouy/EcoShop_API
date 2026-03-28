<?php

namespace App\service;

use App\Repository\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function getCategoryById(int $id)
    {
        return $this->categoryRepository->findById($id);
    }

    public function updateCategory(int $id, array $data)
    {
        $category = $this->getCategoryById($id);
        if (!$category) {
            throw new \Exception('Category not found');
        }
        return $this->categoryRepository->update($category, $data);
    }

    public function deleteCategory(int $id)
    {
        $category = $this->getCategoryById($id);
        if (!$category) {
            throw new \Exception('Category not found');
        }
        return $this->categoryRepository->delete($category);
    }
    public function getAllCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }
}