<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\WebhookEvent;

use Workup\PayumStripe\Token\TokenHashKeysInterface;
use Stripe\Event;

final class AuthorizedPaymentIntentManuallyCanceledAction extends AbstractPaymentIntentAction
{
    protected function getSupportedEventTypes(): array
    {
        return [
            Event::PAYMENT_INTENT_CANCELED,
        ];
    }

    protected function getSupportedCaptureMethod(): string
    {
        return 'manual';
    }

    public function getTokenHashMetadataKeyName(): string
    {
        return TokenHashKeysInterface::CANCEL_TOKEN_HASH_KEY_NAME;
    }
}
