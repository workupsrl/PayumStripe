<?php

namespace Tests\Workup\PayumStripe\Request\StripeCheckoutSession\Api;

use Workup\PayumStripe\Request\StripeCheckoutSession\Api\RedirectToCheckout;
use Payum\Core\Request\Generic;
use PHPUnit\Framework\TestCase;

final class RedirectToCheckoutTest extends TestCase
{
    public function testShouldBeSubClassOfGeneric(): void
    {
        $redirectToCheckout = new RedirectToCheckout([]);

        $this->assertInstanceOf(Generic::class, $redirectToCheckout);
    }
}
