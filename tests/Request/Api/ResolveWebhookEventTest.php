<?php

namespace Tests\Workup\PayumStripe\Request\Api;

use Workup\PayumStripe\Request\Api\ResolveWebhookEvent;
use Payum\Core\Model\Token;
use Payum\Core\Request\Convert;
use PHPUnit\Framework\TestCase;

final class ResolveWebhookEventTest extends TestCase
{
    public function testShouldBeSubClassOfConvert(): void
    {
        $resolveWebhookEvent = new ResolveWebhookEvent(new Token());

        $this->assertInstanceOf(Convert::class, $resolveWebhookEvent);
    }
}
