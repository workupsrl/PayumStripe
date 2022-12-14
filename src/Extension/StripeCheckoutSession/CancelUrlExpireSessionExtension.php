<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Extension\StripeCheckoutSession;

use Workup\PayumStripe\Request\Api\Resource\AbstractCustomCall;
use Workup\PayumStripe\Request\Api\Resource\ExpireSession;
use Payum\Core\Extension\Context;
use Stripe\Checkout\Session;

final class CancelUrlExpireSessionExtension extends AbstractCancelUrlExtension
{
    public function getSupportedObjectName(): string
    {
        return Session::OBJECT_NAME;
    }

    public function createNewRequest(string $id, Context $context): ?AbstractCustomCall
    {
        return new ExpireSession($id);
    }
}
