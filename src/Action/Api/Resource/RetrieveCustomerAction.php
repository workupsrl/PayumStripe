<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveCustomer;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\Customer;

final class RetrieveCustomerAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = Customer::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrieveCustomer;
    }
}
