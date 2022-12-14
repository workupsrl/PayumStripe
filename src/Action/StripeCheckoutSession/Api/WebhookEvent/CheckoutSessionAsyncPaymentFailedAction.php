<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\StripeCheckoutSession\Api\WebhookEvent;

use Workup\PayumStripe\Action\Api\WebhookEvent\AbstractPaymentAction;
use Stripe\Event;

final class CheckoutSessionAsyncPaymentFailedAction extends AbstractPaymentAction
{
    protected function getSupportedEventTypes(): array
    {
        return [
            Event::CHECKOUT_SESSION_ASYNC_PAYMENT_FAILED,
        ];
    }
}
