<?php

declare(strict_types=1);

namespace Workup\PayumStripe\Action\Api\Resource;

use Workup\PayumStripe\Request\Api\Resource\CancelSubscription;
use Workup\PayumStripe\Request\Api\Resource\CustomCallInterface;
use Workup\PayumStripe\Request\Api\Resource\RetrieveInterface;
use Stripe\ApiResource;
use Stripe\Subscription;

final class CancelSubscriptionAction extends AbstractRetrieveAction
{
    protected $apiResourceClass = Subscription::class;

    public function supportAlso(RetrieveInterface $request): bool
    {
        return $request instanceof CancelSubscription;
    }

    /**
     * @param CustomCallInterface&RetrieveInterface $request
     */
    public function retrieveApiResource(RetrieveInterface $request): ApiResource
    {
        /** @var Subscription $apiResource */
        $apiResource = parent::retrieveApiResource($request);

        return $this->cancelSubscription($apiResource, $request);
    }

    public function cancelSubscription(Subscription $apiResource, CustomCallInterface $request): Subscription
    {
        return $apiResource->cancel(
            $request->getCustomCallParameters(),
            $request->getCustomCallOptions()
        );
    }
}
