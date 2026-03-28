<?php

namespace App\repository;

use App\Models\Category;
class CategoryRepository
{
    public function create(array $data): Category
    {
        return Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null
        ]);
    }

    public function findById(int $id): Category
    {
        $category = Category::find($id);
        if (!$category) {
            throw new \Exception("Category not found");
        }
        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    public function getAllCategories()
    {
        return Category::all();
    }
}