<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\StripeCheckoutSession\Api;

use Workup\PayumStripe\Action\Api\StripeApiAwareTrait;
use Workup\PayumStripe\Api\StripeCheckoutSessionApiInterface;

/**
 * @property StripeCheckoutSessionApiInterface $api
 */
trait StripeCheckoutSessionApiAwareTrait
{
    use StripeApiAwareTrait;

    protected function initApiClass(): void
    {
        $this->apiClass = StripeCheckoutSessionApiInterface::class;
    }
}
