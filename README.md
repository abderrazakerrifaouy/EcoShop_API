# EcoShop API

A full-stack e-commerce application built with a **Laravel 12** REST API back-end and a **Vue 3** front-end, orchestrated with Docker Compose and backed by **PostgreSQL**.

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Getting Started](#getting-started)
  - [Environment Variables](#environment-variables)
  - [Running with Docker](#running-with-docker)
  - [Running without Docker](#running-without-docker)
- [API Reference](#api-reference)
  - [Authentication](#authentication)
  - [Categories](#categories)
  - [Products](#products)
  - [Orders](#orders)
- [Database Schema](#database-schema)
- [License](#license)

---

## Overview

EcoShop is a marketplace API that allows users to register, list products by category, place orders, and manage their own inventory. All protected routes are secured with **Laravel Sanctum** token-based authentication.

## Tech Stack

| Layer       | Technology                             |
|-------------|----------------------------------------|
| Back-end    | PHP 8.2, Laravel 12, Laravel Sanctum   |
| Front-end   | Vue 3, Vite                            |
| Database    | PostgreSQL 16                          |
| DB Admin    | pgAdmin 4                              |
| Container   | Docker, Docker Compose                 |

## Project Structure

```
EcoShop_API/
├── backEnd/
│   ├── dockerfile
│   ├── apache.conf
│   └── src/                  # Laravel application
│       ├── app/
│       │   ├── Http/Controllers/api/
│       │   ├── Models/
│       │   ├── repository/
│       │   └── service/
│       ├── database/
│       ├── routes/api.php
│       └── ...
├── frontEnd/
│   ├── dockerfile
│   └── app/                  # Vue 3 application
├── docker-compose.yml
└── README.md
```

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) & [Docker Compose](https://docs.docker.com/compose/install/)
- Or locally: PHP 8.2+, Composer, Node.js 18+, PostgreSQL 16

## Getting Started

### Environment Variables

Copy the example env file and configure it:

```bash
cp backEnd/src/.env.example backEnd/src/.env
```

Key variables to update for Docker usage:

```dotenv
APP_KEY=          # generate with: php artisan key:generate
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=ecoshop
DB_USERNAME=admin
DB_PASSWORD=admin123
```

### Running with Docker

```bash
docker-compose up --build
```

| Service   | URL                          |
|-----------|------------------------------|
| API       | http://localhost:8081/api    |
| Vue app   | http://localhost:8080        |
| pgAdmin   | http://localhost:5050        |

pgAdmin credentials: `admin@admin.com` / `admin123`

After the containers are up, run the database migrations:

```bash
docker exec php_server php artisan migrate
```

### Running without Docker

```bash
# Back-end
cd backEnd/src
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve          # http://localhost:8000

# Front-end (separate terminal)
cd frontEnd/app
npm install
npm run dev                # http://localhost:5173
```

## API Reference

All protected routes require the `Authorization: Bearer <token>` header.

Base URL: `http://localhost:8081/api`

### Authentication

| Method | Endpoint      | Auth | Description            |
|--------|---------------|------|------------------------|
| POST   | `/register`   | No   | Register a new user    |
| POST   | `/login`      | No   | Login and receive token|
| POST   | `/logout`     | Yes  | Revoke current token   |

**Register / Login request body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "secret123"
}
```

### Categories

| Method | Endpoint             | Auth | Description          |
|--------|----------------------|------|----------------------|
| GET    | `/categories`        | Yes  | List all categories  |
| POST   | `/categories/create` | Yes  | Create a category    |

### Products

| Method | Endpoint                              | Auth | Description                        |
|--------|---------------------------------------|------|------------------------------------|
| GET    | `/products`                           | Yes  | List all products                  |
| GET    | `/products/show/{id}`                 | Yes  | Get a single product               |
| GET    | `/products/user/{id}`                 | Yes  | Get products by user               |
| GET    | `/products/category/{categoryId}`     | Yes  | Get products by category           |
| GET    | `/products/searchProducts/{query}`    | Yes  | Search products                    |
| POST   | `/products`                           | Yes  | Create a product                   |
| PUT    | `/products/{id}`                      | Yes  | Update a product                   |
| DELETE | `/products/{id}`                      | Yes  | Delete a product                   |

**Create / Update product body:**
```json
{
  "category_id": 1,
  "name": "Reusable Bottle",
  "description": "Eco-friendly water bottle",
  "price": 19.99,
  "stock": 100,
  "image": "bottle.png"
}
```

### Orders

| Method | Endpoint                      | Auth | Description                  |
|--------|-------------------------------|------|------------------------------|
| GET    | `/orders/{id}`                | Yes  | Get a single order           |
| GET    | `/orders/user/{userId}`       | Yes  | Get orders by user           |
| GET    | `/orders/status/{status}`     | Yes  | Get orders by status         |
| POST   | `/orders`                     | Yes  | Create an order              |
| PUT    | `/orders/{id}`                | Yes  | Update an order              |
| PUT    | `/orders/{id}/status`         | Yes  | Update order status          |
| DELETE | `/orders/{id}`                | Yes  | Delete an order              |

## Database Schema

| Table        | Key Columns                                               |
|--------------|-----------------------------------------------------------|
| `users`      | id, name, email, password                                 |
| `categories` | id, name                                                  |
| `products`   | id, category_id, user_id, name, description, price, stock, image |
| `orders`     | id, user_id, status, total                                |
| `order_items`| id, order_id, product_id, quantity, price                 |

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
