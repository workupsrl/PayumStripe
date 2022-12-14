<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\WebhookEvent;

use Stripe\Event;

final class PaymentIntentCanceledAction extends AbstractPaymentIntentAction
{
    protected function getSupportedEventTypes(): array
    {
        return [
            Event::PAYMENT_INTENT_CANCELED,
        ];
    }

    protected function getSupportedCaptureMethod(): string
    {
        return 'automatic';
    }
}
