<?php

declare(strict_types=1);

namespace App\Tests\E2E\Auth;

use App\Tests\Fixture\TenantFixture;
use App\Tests\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LoginTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures();
    }

    private function loadFixtures(): void
    {
        $em     = static::getContainer()->get('doctrine')->getManager();
        $loader = new Loader();
        $loader->addFixture(new TenantFixture());
        $loader->addFixture(
            new UserFixture(
                static::getContainer()->get('security.user_password_hasher')
            )
        );

        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }

    public function test_login_returns_token_with_valid_credentials(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/auth/login',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email'       => 'buyer@acme.com',
                'password'    => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('user', $data);
        $this->assertSame('buyer@acme.com', $data['user']['email']);
        $this->assertSame('BUYER', $data['user']['role']);
    }

    public function test_login_returns_401_with_wrong_password(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/auth/login',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email'       => 'buyer@acme.com',
                'password'    => 'wrongpassword',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(401);
    }

    public function test_login_returns_401_with_unknown_email(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/auth/login',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email'       => 'nobody@acme.com',
                'password'    => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(401);
    }
}