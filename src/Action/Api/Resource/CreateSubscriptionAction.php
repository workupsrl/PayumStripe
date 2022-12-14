<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CreateInterface;
use Workup\PayumStripe\Request\Api\Resource\CreateSubscription;
use Stripe\Subscription;

final class CreateSubscriptionAction extends AbstractCreateAction
{
    protected $apiResourceClass = Subscription::class;

    public function supportAlso(CreateInterface $request): bool
    {
        return $request instanceof CreateSubscription;
    }
}
