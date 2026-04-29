<?php

declare(strict_types=1);

namespace App\Tests\E2E\Venue;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateVenueTest extends WebTestCase
{
    private function getToken(object $client, string $slug = 'acme'): string
    {
        $client->request(
            method: 'POST',
            uri: '/auth/login',
            content: json_encode([
                'tenant_slug' => $slug,
                'email' => 'admin@acme.com',
                'password' => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        return json_decode($client->getResponse()->getContent(), true)['token'];
    }

    public function test_creates_venue_successfully(): void
    {
        $client = static::createClient();
        $token = $this->getToken($client);

        $client->request(
            method: 'POST',
            uri: '/venues',
            content: json_encode([
                'name' => 'Grand Hall',
                'address' => '1 rue de la Paix',
                'city' => 'Paris',
                'country' => 'FR',
                'capacity' => 500,
            ]),
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('Grand Hall', $data['name']);
        $this->assertSame('Paris', $data['city']);
    }

    public function test_returns_401_without_token(): void
    {
        $client = static::createClient();

        $client->request(
            method: 'POST',
            uri: '/venues',
            content: json_encode([
                'name' => 'Grand Hall',
                'address' => '1 rue de la Paix',
                'city' => 'Paris',
                'country' => 'FR',
                'capacity' => 500,
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(401);
    }

    public function test_returns_403_for_buyer_role(): void
    {
        $client = static::createClient();
        $token = $this->getToken($client);

        $client->request(
            method: 'POST',
            uri: '/auth/login',
            content: json_encode([
                'tenant_slug' => 'acme',
                'email' => 'buyer@acme.com',
                'password' => 'secret123',
            ]),
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $buyerToken = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request(
            method: 'POST',
            uri: '/venues',
            content: json_encode([
                'name' => 'Grand Hall',
                'address' => '1 rue de la Paix',
                'city' => 'Paris',
                'country' => 'FR',
                'capacity' => 500,
            ]),
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $buyerToken,
            ],
        );

        $this->assertResponseStatusCodeSame(403);
    }
}
