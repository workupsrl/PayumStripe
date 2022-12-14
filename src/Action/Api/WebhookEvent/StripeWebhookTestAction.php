<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\WebhookEvent;

use Workup\PayumStripe\Request\Api\WebhookEvent\WebhookEvent;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpResponse;
use Stripe\Event;

/**
 * This class exists to avoid 500 error when testing the Stripe Webhook.
 */
final class StripeWebhookTestAction extends AbstractWebhookEventAction
{
    protected function getSupportedEventTypes(): array
    {
        return [
            Event::CHECKOUT_SESSION_COMPLETED,
            Event::PAYMENT_INTENT_CANCELED,
            Event::SETUP_INTENT_CANCELED,
        ];
    }

    /**
     * @param WebhookEvent $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        throw new HttpResponse('Webhook test succeeded !');
    }

    /**
     * @param WebhookEvent $request
     */
    public function supports($request): bool
    {
        if (false === parent::supports($request)) {
            return false;
        }

        $id = $this->retrieveEventId($request);

        return 'evt_00000000000000' === $id;
    }

    private function retrieveEventId(WebhookEvent $request): ?string
    {
        $eventWrapper = $request->getEventWrapper();

        if (null === $eventWrapper) {
            return null;
        }

        return $eventWrapper->getEvent()->id;
    }
}
