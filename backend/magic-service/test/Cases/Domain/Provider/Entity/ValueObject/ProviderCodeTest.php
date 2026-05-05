<?php

declare(strict_types=1);
/**
 * Copyright (c) The Magic , Distributed under the software license
 */

namespace HyperfTest\Cases\Domain\Provider\Entity\ValueObject;

use App\Domain\Provider\DTO\Item\GoogleProviderConfigItem;
use App\Domain\Provider\DTO\Item\ProviderConfigItem;
use App\Domain\Provider\Entity\ValueObject\Category;
use App\Domain\Provider\Entity\ValueObject\ProviderCode;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ProviderCodeTest extends TestCase
{
    public function testProviderControlAllowlistRules(): void
    {
        $this->assertTrue(ProviderCode::DashScope->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::Volcengine->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::DeepSeek->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::Tencent->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::Baidu->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::SCNet->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::Moonshot->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::BigModel->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::MiniMax->isInProviderControlLlmAllowlist());
        $this->assertTrue(ProviderCode::SiliconFlow->isInProviderControlLlmAllowlist());
        $this->assertFalse(ProviderCode::OpenAI->isInProviderControlLlmAllowlist());

        $this->assertTrue(ProviderCode::Qwen->isInProviderControlAllowlist(Category::VLM));
        $this->assertTrue(ProviderCode::VolcengineArk->isInProviderControlAllowlist(Category::VLM));
        $this->assertTrue(ProviderCode::VolcengineArk->isInProviderControlAllowlist(Category::VGM));
        $this->assertFalse(ProviderCode::OpenRouter->isInProviderControlAllowlist(Category::VLM));
    }

    public function testDefaultUrlsAndAllowedPrimaryDomains(): void
    {
        $this->assertSame(
            'https://dashscope.aliyuncs.com/compatible-mode/v1',
            ProviderCode::DashScope->getDefaultUrl()
        );
        $this->assertSame(
            'https://ark.cn-beijing.volces.com/api/v3',
            ProviderCode::Volcengine->getDefaultUrl()
        );
        $this->assertSame(
            'https://ark.cn-beijing.volces.com/api/v3',
            ProviderCode::VolcengineArk->getDefaultUrl()
        );
        $this->assertSame(
            'https://api.deepseek.com',
            ProviderCode::DeepSeek->getDefaultUrl()
        );
        $this->assertSame(
            'https://api.hunyuan.cloud.tencent.com/v1',
            ProviderCode::Tencent->getDefaultUrl()
        );
        $this->assertSame(
            'https://qianfan.baidubce.com/v2',
            ProviderCode::Baidu->getDefaultUrl()
        );
        $this->assertSame(
            'https://api.scnet.cn/api/llm/v1',
            ProviderCode::SCNet->getDefaultUrl()
        );
        $this->assertSame(
            'https://api.moonshot.cn/v1',
            ProviderCode::Moonshot->getDefaultUrl()
        );
        $this->assertSame(
            'https://open.bigmodel.cn/api/paas/v4',
            ProviderCode::BigModel->getDefaultUrl()
        );
        $this->assertSame(
            'https://api.minimaxi.com/v1',
            ProviderCode::MiniMax->getDefaultUrl()
        );
        $this->assertSame(
            'https://api.siliconflow.cn/v1',
            ProviderCode::SiliconFlow->getDefaultUrl()
        );
        $this->assertSame('', ProviderCode::OpenAI->getDefaultUrl());

        $this->assertSame(['aliyuncs.com'], ProviderCode::DashScope->getAllowedPrimaryDomains());
        $this->assertSame(['volces.com'], ProviderCode::Volcengine->getAllowedPrimaryDomains());
        $this->assertSame(['volces.com'], ProviderCode::VolcengineArk->getAllowedPrimaryDomains());
        $this->assertSame(['deepseek.com'], ProviderCode::DeepSeek->getAllowedPrimaryDomains());
        $this->assertSame(['tencent.com'], ProviderCode::Tencent->getAllowedPrimaryDomains());
        $this->assertSame(['baidubce.com'], ProviderCode::Baidu->getAllowedPrimaryDomains());
        $this->assertSame(['scnet.cn'], ProviderCode::SCNet->getAllowedPrimaryDomains());
        $this->assertSame(['moonshot.cn'], ProviderCode::Moonshot->getAllowedPrimaryDomains());
        $this->assertSame(['bigmodel.cn'], ProviderCode::BigModel->getAllowedPrimaryDomains());
        $this->assertSame(['minimaxi.com'], ProviderCode::MiniMax->getAllowedPrimaryDomains());
        $this->assertSame(['siliconflow.cn'], ProviderCode::SiliconFlow->getAllowedPrimaryDomains());
        $this->assertSame([], ProviderCode::OpenAI->getAllowedPrimaryDomains());
    }

    public function testAllowedPrimaryDomainUrlValidation(): void
    {
        $this->assertTrue(
            ProviderCode::DashScope->isAllowedPrimaryDomainUrl('https://dashscope.aliyuncs.com/compatible-mode/v1')
        );
        $this->assertTrue(
            ProviderCode::Tencent->isAllowedPrimaryDomainUrl('https://api.hunyuan.cloud.tencent.com/v1')
        );
        $this->assertTrue(
            ProviderCode::Baidu->isAllowedPrimaryDomainUrl('https://qianfan.baidubce.com/v2')
        );
        $this->assertTrue(
            ProviderCode::BigModel->isAllowedPrimaryDomainUrl('https://open.bigmodel.cn/api/paas/v4')
        );
        $this->assertTrue(
            ProviderCode::SiliconFlow->isAllowedPrimaryDomainUrl('https://api.siliconflow.cn/v1/chat/completions')
        );
        $this->assertTrue(
            ProviderCode::VolcengineArk->isAllowedPrimaryDomainUrl('https://ark.cn-beijing.volces.com/api/v3')
        );
        $this->assertTrue(
            ProviderCode::VolcengineArk->isAllowedPrimaryDomainUrl('https://sub.ark.cn-beijing.volces.com/api/v3')
        );

        $this->assertFalse(
            ProviderCode::DeepSeek->isAllowedPrimaryDomainUrl('https://api.openai.com/v1')
        );
        $this->assertFalse(
            ProviderCode::Volcengine->isAllowedPrimaryDomainUrl('not-a-valid-url')
        );
        $this->assertFalse(
            ProviderCode::SCNet->isAllowedPrimaryDomainUrl('https://api.scnet.com/v1')
        );
        $this->assertFalse(
            ProviderCode::VolcengineArk->isAllowedPrimaryDomainUrl('https://ark.cn-beijing.volces.example.com/api/v3')
        );
    }

    public function testGeminiServiceAccountImplementationConfigSkipsApiKeyValidation(): void
    {
        $config = new GoogleProviderConfigItem([
            'auth_type' => GoogleProviderConfigItem::AUTH_TYPE_SERVICE_ACCOUNT,
            'project_id' => 'magic-vertex',
            'private_key_id' => 'pk-id',
            'private_key' => "-----BEGIN PRIVATE KEY-----\nABCD\n-----END PRIVATE KEY-----\n",
            'client_email' => 'svc@magic-vertex.iam.gserviceaccount.com',
            'client_id' => '1234567890',
            'location' => 'us-central1',
            'url' => 'https://aiplatform.googleapis.com/v1',
        ]);

        $impl = ProviderCode::Gemini->getImplementationConfig($config, 'gemini-2.5-pro');

        // Service-account mode must not be rejected by Odin's empty api_key validation.
        $this->assertTrue($impl['skip_api_key_validation']);
        $this->assertNotSame('', $impl['api_key']);
        $this->assertSame('https://aiplatform.googleapis.com/v1', $impl['base_url']);

        // service_account payload preserved and includes location for Vertex routing.
        $this->assertIsArray($impl['service_account']);
        $this->assertSame('magic-vertex', $impl['service_account']['project_id']);
        $this->assertSame('us-central1', $impl['service_account']['location']);
    }

    public function testGeminiServiceAccountImplementationConfigKeepsUserSuppliedApiKey(): void
    {
        $config = new GoogleProviderConfigItem([
            'auth_type' => GoogleProviderConfigItem::AUTH_TYPE_SERVICE_ACCOUNT,
            'api_key' => 'AIza-user-supplied',
            'project_id' => 'magic-vertex',
            'private_key_id' => 'pk-id',
            'private_key' => 'pk',
            'client_email' => 'svc@magic-vertex.iam.gserviceaccount.com',
            'client_id' => '12345',
            'url' => 'https://aiplatform.googleapis.com/v1',
        ]);

        $impl = ProviderCode::Gemini->getImplementationConfig($config, 'gemini-2.5-pro');

        $this->assertSame('AIza-user-supplied', $impl['api_key']);
        $this->assertTrue($impl['skip_api_key_validation']);
    }

    public function testGeminiApiKeyModeImplementationConfigPreservesEmptyApiKeyForValidation(): void
    {
        $config = new GoogleProviderConfigItem([
            'auth_type' => GoogleProviderConfigItem::AUTH_TYPE_API_KEY,
            'url' => 'https://generativelanguage.googleapis.com/v1beta',
        ]);

        $impl = ProviderCode::Gemini->getImplementationConfig($config, 'gemini-2.5-pro');

        // AI Studio path must still surface "missing api_key" when not configured.
        $this->assertSame('', $impl['api_key']);
        $this->assertFalse($impl['skip_api_key_validation']);
        $this->assertNull($impl['service_account']);
        $this->assertSame('https://generativelanguage.googleapis.com/v1beta', $impl['base_url']);
    }

    public function testGeminiServiceAccountModeWithIncompleteCredentialsDoesNotSkipValidation(): void
    {
        $config = new GoogleProviderConfigItem([
            'auth_type' => GoogleProviderConfigItem::AUTH_TYPE_SERVICE_ACCOUNT,
            'project_id' => 'p',
            // Missing private_key, private_key_id, client_email, client_id intentionally.
            'url' => 'https://aiplatform.googleapis.com/v1',
        ]);

        $impl = ProviderCode::Gemini->getImplementationConfig($config, 'gemini-2.5-pro');

        // Without a usable service account, behave like api_key mode so missing
        // credentials surface validation cleanly.
        $this->assertFalse($impl['skip_api_key_validation']);
        $this->assertNull($impl['service_account']);
        $this->assertSame('', $impl['api_key']);
    }

    public function testGenericProviderImplementationConfigUnaffectedByGeminiChanges(): void
    {
        // Sanity check: non-Google providers still use the OpenAI-compatible shape.
        $config = new ProviderConfigItem([
            'api_key' => 'sk-test',
            'url' => 'https://api.example.com/v1',
        ]);

        $impl = ProviderCode::DeepSeek->getImplementationConfig($config, 'deepseek-chat');

        $this->assertSame('sk-test', $impl['api_key']);
        $this->assertSame('https://api.example.com/v1', $impl['base_url']);
        $this->assertArrayNotHasKey('service_account', $impl);
        $this->assertArrayNotHasKey('skip_api_key_validation', $impl);
    }
}
