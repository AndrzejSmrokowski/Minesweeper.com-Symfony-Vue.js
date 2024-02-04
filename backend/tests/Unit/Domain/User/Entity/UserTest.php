<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\User\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        // Given
        $this->user = new User(1, 'testuser', 'testpassword');
    }

    public function testGetId(): void
    {
        // Then
        self::assertEquals(1, $this->user->getId());
    }

    public function testGetUsername(): void
    {
        // Then
        self::assertEquals('testuser', $this->user->getUsername());
    }

    public function testSetUsername(): void
    {
        // Given
        $newUsername = 'newuser';

        // When
        $this->user->setUsername($newUsername);

        // Then
        self::assertEquals($newUsername, $this->user->getUsername());
    }

    public function testGetPassword(): void
    {
        // Then
        self::assertEquals('testpassword', $this->user->getPassword());
    }

    public function testSetPassword(): void
    {
        // Given
        $newPassword = 'newpassword';

        // When
        $this->user->setPassword($newPassword);

        // Then
        self::assertEquals($newPassword, $this->user->getPassword());
    }
}