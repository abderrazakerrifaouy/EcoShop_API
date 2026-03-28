<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\service\CategoryService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[OA\Post(
        path: '/api/categories/create',
        summary: 'Create a new category',
        tags: ['Categories'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug' , 'description' , 'image'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Electronics'),
                    new OA\Property(
                        property: 'slug', 
                        type: 'string', 
                        example: 'electronics'
                    ) ,
                    new OA\Property(
                        property: 'description', 
                        type: 'string', 
                        example: 'All kinds of electronic devices and gadgets.'
                    ) ,
                    new OA\Property(
                        property: 'image', 
                        type: 'string', 
                        example: 'https://example.com/images/electronics.jpg'
                    )

                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Category created'),
            new OA\Response(response: 422, description: 'Validation Error'),
            new OA\Response(response: 500, description: 'Server Error')
        ]
    )]
    public function store(Request $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->all());
            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/categories',
        summary: 'Get all categories',
        tags: ['Categories'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Successful response')
        ]
    )]

    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}