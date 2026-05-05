<?php

declare(strict_types=1);
/**
 * Copyright (c) The Magic , Distributed under the software license
 */

namespace HyperfTest\Cases\Domain\Provider\DTO\Item;

use App\Domain\Provider\DTO\Item\GoogleProviderConfigItem;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class GoogleProviderConfigItemTest extends TestCase
{
    public function testAuthTypeDefaultsToApiKey(): void
    {
        $item = new GoogleProviderConfigItem();
        $this->assertSame(GoogleProviderConfigItem::AUTH_TYPE_API_KEY, $item->getAuthType());
    }

    public function testEmptyAuthTypeFallsBackToApiKey(): void
    {
        // Regression: a missing/empty auth_type must not silently promote
        // an AI Studio configuration into Vertex (service_account).
        $item = new GoogleProviderConfigItem(['auth_type' => '']);
        $this->assertSame(GoogleProviderConfigItem::AUTH_TYPE_API_KEY, $item->getAuthType());
    }

    public function testNullAuthTypeFallsBackToApiKey(): void
    {
        $item = new GoogleProviderConfigItem();
        $item->setAuthType(null);
        $this->assertSame(GoogleProviderConfigItem::AUTH_TYPE_API_KEY, $item->getAuthType());
    }

    public function testExplicitServiceAccountAuthTypePreserved(): void
    {
        $item = new GoogleProviderConfigItem([
            'auth_type' => GoogleProviderConfigItem::AUTH_TYPE_SERVICE_ACCOUNT,
        ]);
        $this->assertSame(GoogleProviderConfigItem::AUTH_TYPE_SERVICE_ACCOUNT, $item->getAuthType());
    }

    public function testToOdinServiceAccountConfigReturnsNullWhenIncomplete(): void
    {
        $item = new GoogleProviderConfigItem([
            'project_id' => 'magic-vertex',
            // Missing private_key etc.
        ]);

        $this->assertNull($item->toOdinServiceAccountConfig());
    }

    public function testToOdinServiceAccountConfigIncludesLocation(): void
    {
        $item = new GoogleProviderConfigItem([
            'project_id' => 'magic-vertex',
            'private_key_id' => 'pk-id',
            'private_key' => "-----BEGIN PRIVATE KEY-----\nABCD\n-----END PRIVATE KEY-----\n",
            'client_email' => 'svc@magic-vertex.iam.gserviceaccount.com',
            'client_id' => '1234567890',
            'location' => 'us-central1',
        ]);

        $payload = $item->toOdinServiceAccountConfig();

        $this->assertIsArray($payload);
        $this->assertArrayHasKey('location', $payload);
        $this->assertSame('us-central1', $payload['location']);
        $this->assertSame('magic-vertex', $payload['project_id']);
        $this->assertSame('service_account', $payload['type']);
    }

    public function testToOdinServiceAccountConfigExposesEmptyLocationByDefault(): void
    {
        $item = new GoogleProviderConfigItem([
            'project_id' => 'magic-vertex',
            'private_key_id' => 'pk-id',
            'private_key' => 'k',
            'client_email' => 'svc@magic-vertex.iam.gserviceaccount.com',
            'client_id' => '1',
        ]);

        $payload = $item->toOdinServiceAccountConfig();

        // Always present (even when empty) so downstream consumers can read a
        // stable shape and Vertex/Odin can fall back to defaults.
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('location', $payload);
        $this->assertSame('', $payload['location']);
    }
}
