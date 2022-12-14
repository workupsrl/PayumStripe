<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\AllCustomer;
use Workup\PayumStripe\Request\Api\Resource\AllInterface;
use Stripe\Customer;

final class AllCustomerAction extends AbstractAllAction
{
    protected $apiResourceClass = Customer::class;

    public function supportAlso(AllInterface $request): bool
    {
        return $request instanceof AllCustomer;
    }
}
