# Project Step Instructions
Before you begin, ensure you have met the following requirements:

PHP >= 	8.0

Composer

Laravel

# Installation
### 1. Clone the Repository
```
git clone https://github.com/yogendrakinja/gravityer.git
cd gravityer
```
### 2. Install Dependencies
```
composer install
```

### 3. Copy the .env File
```
cp .env.example .env
```
### 4. Generate Application Key
```
php artisan key:generate
```
### 5. Serve the Application
```
php artisan serve
```

Call the api endpoint

POST /api/discount HTTP/1.1
Host: 127.0.0.1:8000
Content-Type: application/json
Content-Length: 74
{
    "prices" : [5, 5, 10, 20, 30, 40, 50, 50, 50, 60],
    "rule":3 // 1 = rule1, 2= rule2, 3= rule3
}