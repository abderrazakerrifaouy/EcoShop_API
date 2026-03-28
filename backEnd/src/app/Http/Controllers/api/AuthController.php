<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use OpenApi\Attributes as OA;
use App\service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: '/api/register',
        summary: 'Register a new user',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Yassine'),
                new OA\Property(property: 'email', type: 'string', example: 'yassine@mail.com'),
                new OA\Property(property: 'password', type: 'string', example: 'password123'),
                new OA\Property(property: 'password_confirmation', type: 'string', example: 'password123')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'User Created')]
    #[OA\Response(response: 422, description: 'Validation Error')]
    public function register(UserRequest $request)
    {
        return $this->authService->register($request->all());
    }

    #[OA\Post(
        path: '/api/login',
        summary: 'Login an existing user',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'yassine@mail.com'),
                new OA\Property(property: 'password', type: 'string', example: 'password123')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Login successful')]
    #[OA\Response(response: 401, description: 'Invalid credentials')]
    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    #[OA\Post(
        path: '/api/logout',
        summary: 'Logout the current user',
        tags: ['Authentication']
    )]
    #[OA\Response(response: 200, description: 'Logout successful')]

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}