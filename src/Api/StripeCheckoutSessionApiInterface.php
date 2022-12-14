<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Api;

interface StripeCheckoutSessionApiInterface extends KeysAwareInterface, PaymentMethodTypesAwareInterface
{
    public const DEFAULT_PAYMENT_METHOD_TYPES = [];
}
