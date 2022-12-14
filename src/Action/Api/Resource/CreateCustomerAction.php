<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateCustomer;
use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Stripe\Customer;

final class CreateCustomerAction extends AbstractCreateAction
{
    protected $apiResourceClass = Customer::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreateCustomer;
    }
}
