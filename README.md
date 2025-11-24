# OpenAPI HTTP Foundation Testing - Test Project

This is a simple PHP test project demonstrating the functionality of [osteel/openapi-httpfoundation-testing](https://github.com/osteel/openapi-httpfoundation-testing) library.

## Overview

This project contains:
- A sample OpenAPI 3.0 specification (`openapi.json`) with user management endpoints
- PHPUnit tests that validate HTTP requests and responses against the OpenAPI spec
- Examples of both valid and invalid requests/responses

## Requirements

- PHP 8.0 or higher
- Composer

## Installation

Install dependencies using Composer:

```bash
composer install
```

## Project Structure

```
.
├── composer.json           # Project dependencies
├── phpunit.xml            # PHPUnit configuration
├── openapi.json           # OpenAPI 3.0 specification
├── tests/
│   └── OpenApiValidationTest.php   # Test cases
└── README.md
```

## OpenAPI Specification

The `openapi.json` file defines a sample API with the following endpoints:

- `GET /users` - List all users (with optional `limit` query parameter)
- `POST /users` - Create a new user
- `GET /users/{userId}` - Get a specific user by ID

Each endpoint has defined request/response schemas with validation rules.

## Running Tests

Run all tests:

```bash
./vendor/bin/phpunit
```

Run with verbose output:

```bash
./vendor/bin/phpunit --testdox
```

## Test Cases

The test suite includes the following scenarios:

1. **Valid GET request** - Tests a valid request to list users with proper response
2. **Invalid query parameter** - Tests request with query parameter exceeding maximum value
3. **Valid POST request** - Tests creating a user with valid data
4. **Invalid request body** - Tests POST with missing required fields
5. **Invalid response body** - Tests when API returns incomplete data
6. **Valid user lookup** - Tests fetching a specific user by ID
7. **404 Not Found** - Tests proper handling of not found responses
8. **Invalid email format** - Tests validation of email format in responses

## How It Works

The tests use the `osteel/openapi-httpfoundation-testing` library to:

1. Load the OpenAPI specification from `openapi.json`
2. Create Symfony HTTP Foundation Request and Response objects
3. Validate both requests and responses against the spec
4. Assert whether they conform to the defined schema

Example test:

```php
// Create a validator from the OpenAPI spec
$validator = ValidatorBuilder::fromJson(__DIR__ . '/../openapi.json')->getValidator();

// Create a request
$request = Request::create('/v1/users?limit=5', 'GET');

// Create a response
$response = new Response(json_encode($data), 200);

// Validate against the spec
$result = $validator->validate($request, $response);

// Check if valid
$this->assertTrue($result->isValid());
```

## Learning Resources

- [osteel/openapi-httpfoundation-testing Documentation](https://github.com/osteel/openapi-httpfoundation-testing)
- [OpenAPI Specification](https://swagger.io/specification/)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

## License

This is a test project for demonstration purposes.
