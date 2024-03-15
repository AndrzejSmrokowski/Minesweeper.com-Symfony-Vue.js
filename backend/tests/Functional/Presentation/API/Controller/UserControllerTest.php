<?php
declare(strict_types=1);

namespace App\Tests\Functional\Presentation\API\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    /**
     * @throws ToolsException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $container = $this->client->getKernel()->getContainer();

        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $metadata = $doctrine->getManager()->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);
    }

    public function testCreateUserSuccessfully(): void
    {
        // Given
        $userData = [
            'username' => 'testuser123',
            'email' => 'testemail@gmail.com',
            'password' => 'Gurlox12321!!@!@',
        ];

        // When
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($userData));

        // Then
        if ($this->client->getResponse()->getStatusCode() !== Response::HTTP_CREATED) {
            echo $this->client->getResponse()->getContent();
        }
        static::assertStringContainsString('User created successfully', $this->client->getResponse()->getContent());
        static::assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        static::assertJson($this->client->getResponse()->getContent());
    }

    public function testCreateUserWithExistingCredentials(): void
    {
        // Given
        $existingUserData = [
            'username' => 'existinguser',
            'email' => 'existinguser123@example.com',
            'password' => 'Password!123',
        ];
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($existingUserData));

        // When
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($existingUserData));

        // Then
         static::assertStringContainsString('User already exists with email: existinguser123@example.com and username: existinguser', $this->client->getResponse()->getContent());
        static::assertEquals(Response::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
        static::assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteUser(): void
    {
        // Given
        $userData = [
            'username' => 'testuser123',
            'email' => 'testemail@gmail.com',
            'password' => 'Gurlox12321!!@!@',
        ];

        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($userData));
        $response = json_decode($this->client->getResponse()->getContent(), true);
        static::assertStringContainsString('User created successfully', $this->client->getResponse()->getContent());

        $userId = $response['userId'];

        // When
        $this->client->request('DELETE', "/users/$userId");

        // Then
        static::assertStringContainsString('User deleted successfully', $this->client->getResponse()->getContent());
        static::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Check if user is really deleted
        $this->client->request('GET', "/users/$userId");
        static::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateUserWithInvalidEmail(): void
    {
        // Given
        $userData = [
            'username' => 'testuser123',
            'email' => 'invalidemail',
            'password' => 'Gurlox12321!!@!@',
        ];

        // When
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($userData));

        // Then
        static::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        static::assertStringContainsString('Invalid email format', $this->client->getResponse()->getContent());
    }

    public function testCreateUserWithInvalidPassword(): void
    {
        // Given
        $userData = [
            'username' => 'testuser123',
            'email' => 'testemail@gmail.com',
            'password' => 'invalidpassword',
        ];

        // When
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($userData));

        // Then
        static::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        static::assertStringContainsString('Password must contain at least one uppercase letter.', $this->client->getResponse()->getContent());
    }

    public function testCreateUserWithShortUsername(): void
    {
        // Given
        $userData = [
            'username' => 'tu',
            'email' => 'testemail@gmail.com',
            'password' => 'Gurlox12321!!@!@',
        ];

        // When
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($userData));

        // Then
        static::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        static::assertStringContainsString('Username must be between 3 and 20 characters.', $this->client->getResponse()->getContent());
    }

    public function testCreateUserWithExistingUsername(): void
    {
        // Given
        $existingUserData = [
            'username' => 'existinguser',
            'email' => 'existinguser123@example.com',
            'password' => 'Password!123',
        ];
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($existingUserData));

        $newUserDataWithSameUsername = [
            'username' => 'existinguser',
            'email' => 'differentemail@example.com',
            'password' => 'Password!123',
        ];

        // When
        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($newUserDataWithSameUsername));

        // Then
        static::assertStringContainsString('User already exists with username: existinguser', $this->client->getResponse()->getContent());
        static::assertEquals(Response::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
        static::assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteNonExistentUser(): void
    {
        // Given
        $nonExistentUserId = Uuid::uuid4()->toString();

        // When
        $this->client->request('DELETE', "/users/$nonExistentUserId");

        // Then
        static::assertStringContainsString("User with id $nonExistentUserId not found", $this->client->getResponse()->getContent());
        static::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testGetUserById(): void
    {
        // Given
        $userData = [
            'username' => 'testuser123',
            'email' => 'testemail@gmail.com',
            'password' => 'Gurlox12321!!@!@',
        ];

        $this->client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($userData));
        $response = json_decode($this->client->getResponse()->getContent(), true);
        static::assertStringContainsString('User created successfully', $this->client->getResponse()->getContent());

        $userId = $response['userId'];

        // When
        $this->client->request('GET', "/users/$userId");

        // Then
        static::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertJson($this->client->getResponse()->getContent());

        $userResponse = json_decode($this->client->getResponse()->getContent(), true);

        static::assertEquals($userId, $userResponse['id']);
        static::assertEquals($userData['username'], $userResponse['username']);
        static::assertEquals($userData['email'], $userResponse['email']);
    }

}