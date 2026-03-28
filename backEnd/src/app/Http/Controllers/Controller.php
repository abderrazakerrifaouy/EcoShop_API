<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Mon API Documentation",
    version: "1.0.0",
    description: "Documentation dyal l-API b PHP 8.2 Attributes"
)]
#[OA\Server(url: 'http://localhost:8081', description: "API Server")] 

// --- HADA HOWA L-JIDID ---
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Dkhel l-Token dialk hna bach t-authontifi"
)]
// -------------------------

#[OA\Tag(name: 'Authentication', description: 'Endpoints related to user authentication')]
#[OA\Tag(name: 'Categories', description: 'Endpoints related to product categories')]
#[OA\Tag(name: 'Products', description: 'Endpoints related to products')]
#[OA\Tag(name: 'Orders', description: 'Endpoints related to orders')]
#[OA\Tag(name: 'Users', description: 'Endpoints related to user management')]

abstract class Controller
{
    //
}