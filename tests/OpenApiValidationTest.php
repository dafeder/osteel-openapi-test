<?php

namespace Tests;

use Osteel\OpenApi\Testing\ValidatorBuilder;
use Osteel\OpenApi\Testing\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OpenApiValidationTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Load the OpenAPI spec and create a validator
        $this->validator = ValidatorBuilder::fromJson(__DIR__ . '/../openapi.json')->getValidator();
    }

    /**
     * Helper method to simulate loading OpenAPI spec from an HTTP response
     */
    private function getValidatorFromHttpResponse(): ValidatorInterface
    {
        // Simulate fetching the OpenAPI spec from a network call
        $specContent = file_get_contents(__DIR__ . '/../openapi.json');
        
        // Create a mock HTTP response containing the spec
        $response = new Response(
            $specContent,
            200,
            ['Content-Type' => 'application/json']
        );
        
        // Get the JSON from the response body
        $jsonString = $response->getContent();
        
        // Create validator from the JSON string
        return ValidatorBuilder::fromJsonString($jsonString)->getValidator();
    }

    /**
     * Test loading OpenAPI spec from HTTP response (simulating network call)
     */
    public function testValidatorLoadedFromHttpResponse(): void
    {
        // Get a validator that was loaded from an HTTP response
        $validator = $this->getValidatorFromHttpResponse();
        
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
        $responseData = (object) [
          'id' => 1,
          'name' => 'John Doe',
          'email' => 'john@example.com',
          'age' => 30
        ];

        $response = new Response(
            json_encode($responseData),
            200,
            ['Content-Type' => 'application/json']
        );

        // This should work the same as loading from file
        $result = $validator->validate($response, '/api/1/metastore/schemas/dataset/items/{identifier}', 'GET');
        
        $this->assertTrue($result, 'Validator loaded from HTTP response should work correctly');
    }

}
