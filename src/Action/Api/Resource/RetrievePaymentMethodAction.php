<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrievePaymentMethod;
use Stripe\PaymentMethod;

final class RetrievePaymentMethodAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = PaymentMethod::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrievePaymentMethod;
    }
}
