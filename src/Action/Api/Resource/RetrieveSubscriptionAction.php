<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveSubscription;
use Stripe\Subscription;

final class RetrieveSubscriptionAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = Subscription::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof RetrieveSubscription;
    }
}
