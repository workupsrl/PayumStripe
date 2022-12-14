<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreatePaymentMethod;
use Stripe\PaymentMethod;

final class CreatePaymentMethodAction extends AbstractCreateAction
{
    protected $apiResourceClass = PaymentMethod::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreatePaymentMethod;
    }
}
