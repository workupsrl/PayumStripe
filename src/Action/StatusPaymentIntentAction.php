<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;
use Stripe\PaymentIntent;

class StatusPaymentIntentAction extends AbstractStatusAction
{
    public function isMarkedStatus(GetStatusInterface $request, ArrayObject $model): bool
    {
        /** @var string|null $status */
        $status = $model->offsetGet('status');
        if (null === $status) {
            return false;
        }

        if (PaymentIntent::STATUS_SUCCEEDED === $status) {
            $request->markCaptured();

            return true;
        }

        if (PaymentIntent::STATUS_REQUIRES_CAPTURE === $status) {
            $request->markAuthorized();

            return true;
        }

        if (PaymentIntent::STATUS_PROCESSING === $status) {
            $request->markPending();

            return true;
        }

        if ($this->isCanceledStatus($status)) {
            $request->markCanceled();

            return true;
        }

        if ($this->isNewStatus($status)) {
            $request->markNew();

            return true;
        }

        return false;
    }

    /**
     * @see https://stripe.com/docs/payments/intents#payment-intent
     */
    protected function isCanceledStatus(string $status): bool
    {
        return PaymentIntent::STATUS_CANCELED === $status;
    }

    protected function isNewStatus(string $status): bool
    {
        return in_array($status, [
            PaymentIntent::STATUS_REQUIRES_PAYMENT_METHOD, // Customer use the "cancel_url"
            PaymentIntent::STATUS_REQUIRES_CONFIRMATION,
            PaymentIntent::STATUS_REQUIRES_ACTION,
        ], true);
    }

    public function getSupportedObjectName(): string
    {
        return PaymentIntent::OBJECT_NAME;
    }
}
