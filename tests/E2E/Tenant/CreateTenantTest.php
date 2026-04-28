<?php

declare(strict_types=1);

namespace App\Tests\E2E\Tenant;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateTenantTest extends WebTestCase
{
    public function test_creates_tenant_successfully(): void
    {
        $client = static::createClient();

        $client->request(
            method: 'POST',
            uri: '/tenants',
            content: json_encode([
                'name' => 'Acme Corp',
                'slug' => 'acme',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('Acme Corp', $data['name']);
        $this->assertSame('acme', $data['slug']);
    }

    public function test_returns_409_when_slug_already_exists(): void
    {
        $client = static::createClient();

        $payload = json_encode(['name' => 'Acme Corp', 'slug' => 'acme-dup']);

        $client->request('POST', '/tenants', content: $payload, server: ['CONTENT_TYPE' => 'application/json']);
        $client->request('POST', '/tenants', content: $payload, server: ['CONTENT_TYPE' => 'application/json']);

        $this->assertResponseStatusCodeSame(409);
    }

    public function test_returns_422_with_invalid_slug(): void
    {
        $client = static::createClient();

        $client->request(
            method: 'POST',
            uri: '/tenants',
            content: json_encode([
                'name' => 'Acme Corp',
                'slug' => 'Invalid Slug!!',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(422);
    }

    public function test_get_tenant_by_slug(): void
    {
        $client = static::createClient();

        $client->request(
            method: 'POST',
            uri: '/tenants',
            content: json_encode(['name' => 'Acme Corp', 'slug' => 'acme-get']),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $client->request('GET', '/tenants/acme-get');

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('acme-get', $data['slug']);
    }
}
