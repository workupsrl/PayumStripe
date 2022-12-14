<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreatePaymentIntent;
use Stripe\PaymentIntent;

final class CreatePaymentIntentAction extends AbstractCreateAction
{
    protected $apiResourceClass = PaymentIntent::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreatePaymentIntent;
    }
}
