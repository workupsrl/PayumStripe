<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Request\StripeCheckoutSession\Api;

use Payum\Core\Request\Generic;

final class RedirectToCheckout extends Generic
{
    public function __construct(array $model)
    {
        parent::__construct($model);
    }
}
