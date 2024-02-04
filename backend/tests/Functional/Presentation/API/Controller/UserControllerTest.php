<?php

declare(strict_types=1);

namespace App\Tests\Functional\Presentation\API\Controller;

use App\Tests\Functional\FunctionalTestCase;

class UserControllerTest extends FunctionalTestCase
{
    public function testCreateUser(): void
    {
        // Given
        $data = [
            'username' => 'testuser',
            'password' => 'testpassword',
        ];

        // When
        $this->client->request('POST', '/api/user', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Then
        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function testGetUser(): void
    {
        // Assuming a user with ID 1 exists
        $userId = 1; // replace with a valid user ID that exists in your database during the test

        // When
        $this->client->request('GET', '/api/user/' . $userId);

        // Then
        $response = $this->client->getResponse();
        self::assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        self::assertIsArray($responseData);
        self::assertArrayHasKey('id', $responseData);
        self::assertEquals($userId, $responseData['id']);
    }
}
