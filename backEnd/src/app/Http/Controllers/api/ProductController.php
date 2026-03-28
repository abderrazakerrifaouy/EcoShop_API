<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\service\ProductServic;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductServic $productService)
    {
        $this->productService = $productService;
    }
    #[OA\Get(
        path: '/api/products/user/{id}',
        summary: 'Get products by user ID',
        tags: ['Products'] , 
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID of the user',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Response(response: 200, description: 'Successful response')]

    public function getProductsByIdUser($id)
    {
        try {
            $products = $this->productService->getProductsByIdUser($id);
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/products',
        summary: 'Get all products',
        tags: ['Products'] ,
        security: [['bearerAuth' => []]]

    )]
    #[OA\Response(response: 200, description: 'Successful response')]

    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/products/category/{categoryId}',
        summary: 'Get products by category ID',
        tags: ['Products'] ,
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'categoryId',
        in: 'path',
        description: 'ID of the category',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Response(response: 200, description: 'Successful response')]
    public function getProductsByCategoryId($categoryId)
    {
        try {
            $products = $this->productService->getProductsByCategoryId($categoryId);
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/products/searchProducts/{query}',
        summary: 'Search products',
        tags: ['Products'] ,
        security: [['bearerAuth' => []]]

    )]
    #[OA\Response(response: 200, description: 'Successful response')]
    #[OA\Parameter(
        name: 'query',
        in: 'path',
        description: 'Search query for products',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'laptop')
     )]
    public function searchProducts($query)
    {
        try {
            $products = $this->productService->searchProducts($query);
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/products/show/{id}',
        summary: 'Get product by ID',
        tags: ['Products'] ,
        security: [['bearerAuth' => []]]

    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID of the product',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]  
    #[OA\Response(response: 200, description: 'Successful response')]
    #[OA\Response(response: 404, description: 'Product not found')]

    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: '/api/products',
        summary: 'Create a new product',
        tags: ['Products'] ,
        security: [['bearerAuth' => []]]

    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'price', 'user_id'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Product Name'),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 19.99),
                new OA\Property(property: 'description', type: 'string', example: 'Product Description'),
                new OA\Property(property: 'category_id', type: 'integer', example: 1),
                new OA\Property(property: 'user_id', type: 'integer', example: 1),
                new OA\Property(property: 'stock', type: 'integer', example: 10)
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Product created')]
    #[OA\Response(response: 422, description: 'Validation Error')]


    public function store(ProductRequest $request)
    {
        
        try {
            $data = $request->all();
            
            $product = $this->productService->createProduct($data);
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Put(
        path: '/api/products/{id}',
        summary: 'Update an existing product',
        tags: ['Products'] ,
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'price', 'user_id'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Product Name'),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 19.99),
                new OA\Property(property: 'description', type: 'string', example: 'Product Description'),
                new OA\Property(property: 'category_id', type: 'integer', example: 1),
                new OA\Property(property: 'user_id', type: 'integer', example: 1),
                new OA\Property(property: 'stock', type: 'integer', example: 10)

            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path', 
        required: true, 
        description: 'ID of the product to update', 
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Response(response: 200, description: 'Product updated')]
    #[OA\Response(response: 404, description: 'Product not found')]
    public function update(ProductRequest $request, $id)
    {
        try {
            $data = $request->all();
            $product = $this->productService->updateProduct($id, $data);
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Delete(
        path: '/api/products/{id}',
        summary: 'Delete a product',
        tags: ['Products'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID of the product to delete',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Response(response: 200, description: 'Product deleted')]
    #[OA\Response(response: 404, description: 'Product not found')]
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->json(['message' => 'Product deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}