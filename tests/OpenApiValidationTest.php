<?php

namespace Tests;

use Osteel\OpenApi\Testing\ValidatorBuilder;
use Osteel\OpenApi\Testing\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OpenApiValidationTest extends TestCase
{
    /**
     * Helper method to load OpenAPI spec from a JSON string
     */
    private function getValidatorFromJsonString(): ValidatorInterface
    {
        // Load the OpenAPI spec as a string (simulating what happens when fetched from a network call)
        $jsonString = file_get_contents(__DIR__ . '/../openapi.json');
        
        // Create validator from the JSON string
        return ValidatorBuilder::fromJsonString($jsonString)->getValidator();
    }

    /**
     * Test loading OpenAPI spec from JSON string vs file path
     */
    public function testValidator(): void
    {
        $pathValidator = ValidatorBuilder::fromJson(__DIR__ . '/../openapi.json')->getValidator();
      // Get a validator that was loaded from a JSON string
        $stringValidator = $this->getValidatorFromJsonString();
        
        // Now use this validator just like the file-based one
        $request = Request::create(
            '/v1/users?limit=5',
            'GET',
            [],
            [],
            [],
            ['HTTP_HOST' => 'api.example.com', 'HTTPS' => 'on']
        );

        // Mock a valid response
        $responseData = [
          (object) [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 30
          ]
        ];

        $response = new Response(
            json_encode($responseData),
            200,
            ['Content-Type' => 'application/json']
        );

        // First result, from file path:
        $resultFromFile = $pathValidator->validate($response, '/users', 'GET');
        $this->assertTrue($resultFromFile, 'Validator loaded from file path should work correctly');
        
        // This should work the same as loading from file
        $result = $stringValidator->validate($response, '/users', 'GET');
        $this->assertTrue($result, 'Validator loaded from JSON string should work correctly');
    }

}
