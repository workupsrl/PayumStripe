<?php

namespace Tests\Workup\PayumStripe\Action\StripeCheckoutSession\Api;

use Workup\PayumStripe\Action\StripeCheckoutSession\Api\RedirectToCheckoutAction;
use Workup\PayumStripe\Request\StripeCheckoutSession\Api\RedirectToCheckout;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use PHPUnit\Framework\TestCase;
use Tests\Workup\PayumStripe\Action\Api\ApiAwareActionTestTrait;
use Tests\Workup\PayumStripe\Action\GatewayAwareTestTrait;

final class RedirectToCheckoutActionTest extends TestCase
{
    use ApiAwareActionTestTrait;
    use GatewayAwareTestTrait;

    public function testShouldImplements(): void
    {
        $action = new RedirectToCheckoutAction();

        $this->assertInstanceOf(ActionInterface::class, $action);
    }

    public function testShouldNotSupportModelWithoutUrl(): void
    {
        $model = [];
        $action = new RedirectToCheckoutAction();

        $request = new RedirectToCheckout($model);

        $supports = $action->supports($request);
        $this->assertTrue($supports);

        $this->expectException(RequestNotSupportedException::class);
        $action->execute($request);
    }

    public function testShouldSupportAndRedirect(): void
    {
        $model = [
            'url' => 'https://localhost',
        ];
        $action = new RedirectToCheckoutAction();

        $request = new RedirectToCheckout($model);

        $supports = $action->supports($request);
        $this->assertTrue($supports);

        $this->expectException(HttpRedirect::class);
        $action->execute($request);
    }
}
