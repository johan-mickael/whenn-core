<?php

declare(strict_types=1);

namespace App\Tests\E2E\Auth;

use App\Tests\Fixture\TenantFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegisterTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures();
    }

    private function loadFixtures(): void
    {
        $em = static::getContainer()->get('doctrine')->getManager();
        $loader = new Loader();
        $loader->addFixture(new TenantFixture());

        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }

    public function test_register_returns_201_with_valid_data(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/auth/register',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email' => 'newuser@acme.com',
                'password' => 'secret123',
                'first_name' => 'Jean',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('newuser@acme.com', $data['email']);
        $this->assertSame('BUYER', $data['role']);
    }

    public function test_register_returns_409_when_email_already_exists(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/auth/register',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email' => 'duplicate@acme.com',
                'password' => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->client->request(
            method: 'POST',
            uri: '/auth/register',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email' => 'duplicate@acme.com',
                'password' => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(409);
    }

    public function test_register_returns_404_when_tenant_unknown(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/auth/register',
            content: json_encode([
                'tenant_slug' => 'unknown',
                'email' => 'buyer@acme.com',
                'password' => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function test_register_returns_422_with_invalid_email(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/auth/register',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email' => 'not-an-email',
                'password' => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(422);
    }
}