<?php

declare(strict_types=1);

namespace App\Tests\E2E\Tenant;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GetTenantTest extends WebTestCase
{
    public function test_get_tenant_by_slug(): void
    {
        $client = static::createClient();

        $slug = 'acme-' . uniqid();

        $client->request(
            method: 'POST',
            uri: '/tenants',
            content: json_encode(['name' => 'Acme Corp', 'slug' => $slug]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(201);

        $client->request('GET', '/tenants/' . $slug);

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($slug, $data['slug']);
        $this->assertSame('Acme Corp', $data['name']);
    }

    public function test_returns_404_when_tenant_not_found(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tenants/unknown-slug');

        $this->assertResponseStatusCodeSame(404);
    }
}
