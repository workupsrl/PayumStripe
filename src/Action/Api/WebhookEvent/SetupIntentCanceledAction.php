<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\WebhookEvent;

use Stripe\Event;

final class SetupIntentCanceledAction extends AbstractPaymentAction
{
    protected function getSupportedEventTypes(): array
    {
        return [
            Event::SETUP_INTENT_CANCELED,
        ];
    }
}
